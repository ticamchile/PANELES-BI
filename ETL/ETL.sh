#!/bin/bash

cd /home/panelesbi/paneles-bi.bctic.net/

php app/console cam-bi:procesar-archivos --env=prod >> ../ETL/etl.log


#FINANZAS
echo "INSERT INTO INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) (SELECT area, 'M' as granularidad,'1' as dia,mes,anno, CONCAT(UCASE(categoria),'_FINANZAS_',UCASE(tipo)) as CONCEPTO, SUM(valor) as valor FROM GASTO GROUP BY area, CONCAT(mes,'-',anno), CONCEPTO) ON DUPLICATE KEY UPDATE INDICADOR.valor = valor;" | mysql -u pentaho -ppentaho cambi_etl
#SOLICITUD_SERVICIO
echo "INSERT INTO INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) (SELECT area, 'M' as granularidad,'1' as dia,mes,anno, CONCAT(UCASE(categoria),'_SOLICITUD_SERVICIO_',UCASE(estado)) as CONCEPTO, COUNT(*) as valor FROM SOLICITUD_SERVICIO WHERE tipo = 'TOTAL' GROUP BY area, CONCAT(mes,'-',anno), CONCEPTO) ON DUPLICATE KEY UPDATE INDICADOR.valor = valor;" | mysql -u pentaho -ppentaho cambi_etl
#TIEMPO DE ATENCION
echo "INSERT INTO INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) (SELECT area, 'M' as granularidad,'1' as dia,mes,anno, CONCAT(UCASE(categoria),'_SOLICITUD_SERVICIO_TIEMPO_DE_ATENCION') as CONCEPTO, AVG(tiempo_de_atencion) as valor FROM SOLICITUD_SERVICIO WHERE tipo = 'TOTAL' AND estado = 'CUMPLE' GROUP BY area, CONCAT(mes,'-',anno), CONCEPTO) ON DUPLICATE KEY UPDATE INDICADOR.valor = valor;" | mysql -u pentaho -ppentaho cambi_etl

#VACIO EN LA BD CONSOLIDADA:
echo "DELETE FROM cambi_etl.INDICADOR WHERE INDICADOR LIKE 'CONSUMO_SUBGERENCIA_DIPREL' OR indicador LIKE 'CONSUMOS_%_DIPREL_BSM' OR indicador LIKE 'CONSUMOS_%_B_SIPRELEC';"| mysql -u pentaho -ppentaho cambi_etl

echo "INSERT INTO cambi.INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) (SELECT area,granularidad,dia,mes,anno,indicador,valor FROM cambi_etl.INDICADOR where indicador NOT REGEXP \"_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}[\_]\" AND indicador NOT REGEXP \"_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}\" AND indicador NOT LIKE \"CONSUMO_SUBINVENTARIO\_%\_SUBGERENCIA\")  ON DUPLICATE KEY UPDATE cambi.INDICADOR.valor = cambi_etl.INDICADOR.valor;" | mysql -u pentaho -ppentaho cambi_etl

#echo "INSERT INTO cambi.INDICADOR (area,granularidad,dia,mes,anno,indicador,valor) (SELECT area,granularidad,dia,mes,anno,indicador,valor FROM cambi_etl.INDICADOR)  ON DUPLICATE KEY UPDATE cambi.INDICADOR.valor = cambi_etl.INDICADOR.valor;" | mysql -u pentaho -ppentaho cambi_etl
