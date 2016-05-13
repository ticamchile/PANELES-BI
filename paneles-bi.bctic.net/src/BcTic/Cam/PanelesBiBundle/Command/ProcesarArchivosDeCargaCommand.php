<?php

namespace BcTic\Cam\PanelesBiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use BcTic\Cam\PanelesBiBundle\Entity\ArchivoDeCarga;

class ProcesarArchivosDeCargaCommand extends ContainerAwareCommand
{

    protected $em = null;

    protected function configure()
    {
        $this
            ->setName('cam-bi:procesar-archivos')
            ->setDescription('COPIA LOS ARCHIVOS DE DATOS A /data PARA SER PROCESADOS POR EL ETL');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get('doctrine')->getManager();  
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);    

        $output->writeln("COMENZANDO PROCESO");

        $entities = $this->em->getRepository('BcTicCamPanelesBiBundle:ArchivoDeCarga')->findBy(
          array('status' => 'PENDING'),
          array(),
          100,
          0
          );

        foreach ($entities as $entity) {
          try {
            $this->process($entity, $input, $output);  
          } catch (\Exception $e) {
            $output->writeln("  ERROR: ".$e->getMessage());
          }  
        }
        
        $output->writeln("EJECUCION FINALIZADA. Good Bye!");
    }

  private function process(ArchivoDeCarga $archivoDeCarga,InputInterface $input, OutputInterface $output) {
    $output->writeln("   * PROCESANDO: ".$archivoDeCarga->getPath());

    //Existe el archivo:
    $file = $archivoDeCarga->getUploadRootDir().$archivoDeCarga->getCreatedAt().'-'.$archivoDeCarga->getPath();
    if (!is_readable($file)) throw new \Exception("ARCHIVO ".$archivoDeCarga->getCreatedAt().'-'.$archivoDeCarga->getPath()." NO SE ENCUENTRA.");
    if (($handle = fopen($file, "r")) == false) throw new \Exception("ARCHIVO NO SE PUEDE PROCESAR."); 

    //Ahora lo copio

    $rutaDestino = $this->getApplication()->getKernel()->getRootDir().'/Resources/data/'.$archivoDeCarga->getTipo().'/'.$archivoDeCarga->getStatus();

    //EXISTE EL DIRECTORIO???
    if (!is_readable($rutaDestino)) throw new \Exception($rutaDestino.' NO EXISTE.');

    $datasource = $rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION);
    if (!copy($file,$datasource)) throw new \Exception(' NO SE PUDO COPIAR EL ARCHIVO.');
     
    $archivoDeCarga->setNotes($archivoDeCarga->getNotes().chr(10).'* ARCHIVO COPIADO PARA SER PROCESADO POR ETL EN LOS PROXIMOS MINUTOS.');

    $pentaho_etl_path = $this->getContainer()->getParameter('bc_tic_cam_paneles_bi_pentaho_etl_path');
    $pentaho_etl_files_path = $this->getContainer()->getParameter('bc_tic_cam_paneles_bi_pentaho_etl_files_path');

    //AHORA DEBO EJECUTAR - SEGÚN EL TIPO:
    switch ($archivoDeCarga->getTipo()) {

      case "PRESUPUESTO_VS_REAL_TI": 

       //Creo un CSV de referencias:
       $list = array(
            array("AREA","MES","ANNO"),
            array("TI",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
       );

       $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
       foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);

        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'GASTO_REAL_TI.ktr" -trans="GASTO_REAL_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'GASTO_PRESUPUESTO_TI.ktr" -trans="GASTO_PRESUPUESTO_TI" -param:ANNO="'.$archivoDeCarga->getAnno().'" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'GASTO_PROYECCION_TI.ktr" -trans="GASTO_PROYECCION_TI" -param:ANNO="'.$archivoDeCarga->getAnno().'" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        //PRESUPUESTO
        break;

        case "SOLICITUD_SERVICIOS_TI": 

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("TI",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);


        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'SOLICITUD_SERVICIOS_TI.ktr" -trans="SOLICITUD_SERVICIOS_TI" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        /* CRONTAB: INSERT INTO cambi.SOLICITUD_SERVICIO (area,tipo,mes,anno,cantidad,tiempo_de_atencion_promedio, estado, categoria) (SELECT * FROM VIEW_SOLICITUD_SERVICIO) ON DUPLICATE KEY UPDATE cambi.SOLICITUD_SERVICIO.cantidad = cambi_etl.VIEW_SOLICITUD_SERVICIO.cantidad; */
        break;

        case "MESA_DE_AYUDA_UPTIME_TI": 

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("TI",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);


        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'MESA_DE_AYUDA_UPTIME_TI.ktr" -trans="MESA_DE_AYUDA_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");
        

        //ORACLE_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'ORACLE_UPTIME_TI.ktr" -trans="ORACLE_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

       
        //CORREO_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'CORREO_UPTIME_TI.ktr" -trans="CORREO_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        //INTERNET_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'INTERNET_UPTIME_TI.ktr" -trans="INTERNET_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");        

        //REDES_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'REDES_UPTIME_TI.ktr" -trans="REDES_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");        

        //SAMAC_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'SAMAC_UPTIME_TI.ktr" -trans="SAMAC_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");       

        //TOTAL_UPTIME_TI
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'TOTAL_UPTIME_TI.ktr" -trans="TOTAL_UPTIME_TI" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");       

        break;


        case "CONSUMOS_COMPRAS": 

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("COMPRAS",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);


        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'CONSUMOS-COMPRAS.ktr" -trans="CONSUMOS-COMPRAS" -param:CSV_OUTPUT="'.$rutaDestino.'/CONSUMOS-DUMP-'.md5($file).'" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = "echo 454;";//exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Enero/1/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Febrero/2/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Marzo/3/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i  's/Abril/4/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Mayo/5/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Junio/6/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Julio/7/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Agosto/8/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Septiembre/9/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Octubre/10/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Noviembre/11/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = "sed -i 's/Diciembre/12/g' '".$rutaDestino."/CONSUMOS-DUMP-".md5($file).".csv'";
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'CONSUMOS-COMPRAS-CARGA.ktr" -trans="CONSUMOS-COMPRAS-CARGA" -param:CSV_OUTPUT="'.$rutaDestino.'/CONSUMOS-DUMP-'.md5($file).'.csv" -param:AREA="COMPRAS"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = 'echo "DELETE from INDICADOR WHERE area = \"COMPRAS\" AND indicador LIKE CONCAT(\"CONSUMO_SUBGERENCIA_%\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = ' echo "call CONSUMOS_SUBGERENCIAS(\"COMPRAS\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!"); 

        /* CRONTAB: INSERT INTO cambi.SOLICITUD_SERVICIO (area,tipo,mes,anno,cantidad,tiempo_de_atencion_promedio, estado, categoria) (SELECT * FROM VIEW_SOLICITUD_SERVICIO) ON DUPLICATE KEY UPDATE cambi.SOLICITUD_SERVICIO.cantidad = cambi_etl.VIEW_SOLICITUD_SERVICIO.cantidad; */

        break;

        case "SUBINVENTARIOS_COMPRAS": 

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("COMPRAS",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);
        //AHORA TENGO LOS CONSUMOS, DEBO CALCULAR POR SUBGERENCIA
        //CREO INDICADORES POR SUBGERENCIA DEL MES INDICADO/AÑO PERO EN TUPL, LUEGO UNA SQL EN BATH HACE EL "CRUCE".
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'SUBINVENTARIOS-COMPRAS.ktr" -trans="SUBINVENTARIOS_COMPRAS" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        
        $cmd = 'echo "DELETE from INDICADOR WHERE area = \"COMPRAS\" AND indicador LIKE CONCAT(\"CONSUMO_SUBGERENCIA_%\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = ' echo "call CONSUMOS_SUBGERENCIAS(\"COMPRAS\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        break;

        case "PM_COMPRAS": 

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("COMPRAS",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);
        //AHORA TENGO LOS CONSUMOS, DEBO CALCULAR POR SUBGERENCIA
        //CREO INDICADORES POR SUBGERENCIA DEL MES INDICADO/AÑO PERO EN TUPL, LUEGO UNA SQL EN BATH HACE EL "CRUCE".
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'PRECIOSMEDIOS-COMPRAS.ktr" -trans="PRECIOSMEDIOS_COMPRAS" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        $cmd = 'echo "DELETE from INDICADOR WHERE area = \"COMPRAS\" AND indicador LIKE CONCAT(\"CONSUMO_SUBGERENCIA_%\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = ' echo "call CONSUMOS_SUBGERENCIAS(\"COMPRAS\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        break;

        case "MAESTRODEMATERIALES_COMPRAS":

        //Creo un CSV de referencias:
        $list = array(
            array("AREA","MES","ANNO"),
            array("COMPRAS",$archivoDeCarga->getMes(),$archivoDeCarga->getAnno()),
        );

        $fileCsv = fopen($rutaDestino.'/'.md5($file).'.csv',"w+");
        foreach ($list as $line) {
          fputcsv($fileCsv,$line,";");
        }
        fclose($fileCsv);
        
        $cmd = $pentaho_etl_path.'pan.sh -file="'.$pentaho_etl_files_path .'MAESTRODEMATERIALES-COMPRAS.ktr" -trans="MAESTRODEMATERIALES_COMPRAS" -param:CSV="'.$rutaDestino.'/'.md5($file).'.csv" -param:XLS="'.$rutaDestino.'/'.md5($file).'.'.pathinfo($file, PATHINFO_EXTENSION).'"';
        //EJECUTO:
        $output->writeln("   *** EJECUTANDO COMANDO ETL");
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");

        $cmd = 'echo "DELETE from INDICADOR WHERE area = \"COMPRAS\" AND indicador LIKE CONCAT(\"CONSUMO_SUBGERENCIA_%\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);

        $cmd = ' echo "call CONSUMOS_SUBGERENCIAS(\"COMPRAS\");" | mysql -u pentaho -ppentaho cambi_etl';
        $out = exec($cmd);
        $output->writeln("   *** ETL CMD:".$out);
        
        $output->writeln("   *** EJECUTADO COMANDO ETL - OK!");
        break;


    }

    $archivoDeCarga->setStatus('OK');
    $this->em->persist($archivoDeCarga);

    $this->em->flush();

    $output->writeln("   * OK: ".$archivoDeCarga->getPath());

  }  

  protected function printMemoryUsage($output)
    {
        $output->writeln(sprintf('  >>> Memory usage (currently) %dKB/ (max) %dKB', round(memory_get_usage(true) / 1024), memory_get_peak_usage(true) / 1024));
    }
  
}