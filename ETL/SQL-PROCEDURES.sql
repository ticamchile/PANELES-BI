DROP PROCEDURE IF EXISTS CONSUMOS_SUBGERENCIAS;
DROP PROCEDURE IF EXISTS REEMPLAZAR_CONSUMOS_SUBGERENCIAS;
DROP PROCEDURE IF EXISTS CREAR_PM_CONSUMOS_SUBGERENCIAS;
DROP PROCEDURE IF EXISTS CREAR_PERMANENCIA_CONSUMOS_SUBGERENCIAS;


DELIMITER //

CREATE PROCEDURE CREAR_PERMANENCIA_CONSUMOS_SUBGERENCIAS(IN area VARCHAR(45), IN granularidad VARCHAR(1), IN dia INT(11), IN mes INT(11), IN anno INT(11), IN reemplazo VARCHAR(100),IN pm INT(11),IN cantidad VARCHAR(30), IN indicador_aux VARCHAR(100))
BEGIN
  
  # DEBE CREAR TUPLAS DE SUMA 
  DECLARE done INT DEFAULT 0;

  DECLARE permanencia VARCHAR(50);

  DECLARE cur CURSOR FOR SELECT valor from INDICADOR i WHERE i.indicador REGEXP "PERMANENCIA_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}" AND i.indicador = REPLACE(indicador_aux,'PM','PERMANENCIA') AND area = area AND granularidad = granularidad AND dia = dia AND mes = mes AND anno = anno LIMIT 1;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
  
  OPEN cur;
  read_loop: LOOP
 
    FETCH cur INTO permanencia; 
    IF done THEN
        LEAVE read_loop;  
    END IF;

  #AHORA INSERTO Y EN CASO DE DUPLICADO ACTUALIZO - VEO SI ES PERMANENTE O NO_PERMANENTE
  INSERT INTO INDICADOR(area, granularidad,dia,mes,anno,indicador,valor) (SELECT area, granularidad,dia,mes,anno,CONCAT('CONSUMO_SUBGERENCIA_',permanencia,'_',reemplazo), -1 * pm * cantidad as valor_nuevo) ON DUPLICATE KEY UPDATE INDICADOR.valor = INDICADOR.valor + (-1 * pm * cantidad);
 
  END LOOP;
  CLOSE cur; 

END //

CREATE PROCEDURE CREAR_PM_CONSUMOS_SUBGERENCIAS(IN area VARCHAR(45), IN granularidad VARCHAR(1), IN dia INT(11), IN mes INT(11), IN anno INT(11), IN indicador_aux VARCHAR(100),IN cantidad VARCHAR(30), IN buscar VARCHAR(50), IN reemplazo VARCHAR(50))
BEGIN
  
  # DEBE CREAR TUPLAS DE SUMA 
  DECLARE done INT DEFAULT 0;

  DECLARE pm DECIMAL(10);

  #PRIMERO OBTENGO LOS PRECIONS 
  #CONSUMOS_00C4.00BB.002350_SG-DE-DISTRIBUCION
  #PM_00C4.00BB.002350
  DECLARE cur CURSOR FOR SELECT CAST(valor as DECIMAL(10)) from INDICADOR i WHERE i.indicador REGEXP "PM_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}" AND i.indicador = indicador_aux AND area = area AND granularidad = granularidad AND dia = dia AND mes = mes AND anno = anno LIMIT 1;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
  
  OPEN cur;
  read_loop: LOOP
 
    FETCH cur INTO pm; 
    IF done THEN
        LEAVE read_loop;  
    END IF;

    #AHORA INSERTO Y EN CASO DE DUPLICADO ACTUALIZO - VEO SI ES PERMANENTE O NO_PERMANENTE
    call CREAR_PERMANENCIA_CONSUMOS_SUBGERENCIAS(area, granularidad,dia,mes,anno,reemplazo,pm,cantidad,indicador_aux);
 
  END LOOP;
  CLOSE cur; 

END //

CREATE PROCEDURE REEMPLAZAR_CONSUMOS_SUBGERENCIAS(IN buscar VARCHAR(45), IN reemplazo VARCHAR(45),IN area VARCHAR(45))
BEGIN
  
  # DEBE CREAR TUPLAS DE SUMA 
  DECLARE done INT DEFAULT 0;

  DECLARE area_aux VARCHAR(45);
  DECLARE granularidad_aux VARCHAR(1);
  DECLARE dia_aux INT(11);
  DECLARE mes_aux INT(11);
  DECLARE anno_aux INT(11);
  DECLARE indicador_aux VARCHAR(100);
  DECLARE valor_aux VARCHAR(30);

  
  #PRIMERO OBTENGO LAS SUBGERENCIAS
  DECLARE cur CURSOR FOR SELECT area, "M",1, mes,anno,REPLACE(REPLACE(indicador,"CONSUMOS_","PM_"),CONCAT("_",buscar),""), SUM(valor) from INDICADOR WHERE indicador REGEXP CONCAT("CONSUMOS_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}[\_]",buscar) and area = area GROUP BY area,granularidad,dia,mes,anno, indicador;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
  
  OPEN cur;
  read_loop: LOOP
 
    FETCH cur INTO area_aux,granularidad_aux,dia_aux,mes_aux,anno_aux,indicador_aux,valor_aux; 
    IF done THEN
        LEAVE read_loop;  
    END IF;
 
    #AHORA DEBO BUSCAR EL PRECIO DE CADA PRODUCTO POR MES
    #SELECT area, "M",1, mes,anno,REPLACE(REPLACE(indicador,"CONSUMOS_","PM_"),CONCAT("_",buscar),""), SUM(valor) from INDICADOR WHERE indicador REGEXP CONCAT("CONSUMOS_[0-9A-Z]{4}[\.][0-9A-Z]{4}[\.][0-9A-Z]{6}[\_]",buscar) and area = area GROUP BY area,granularidad,dia,mes,anno, indicador;
    call CREAR_PM_CONSUMOS_SUBGERENCIAS(area_aux,granularidad_aux,dia_aux,mes_aux,anno_aux,indicador_aux,valor_aux, buscar, reemplazo);
 
  END LOOP;
  CLOSE cur; 

END //


CREATE PROCEDURE CONSUMOS_SUBGERENCIAS (IN area_aux VARCHAR(45))
BEGIN
  # DEBE CREAR TUPLAS DE SUMA 
  DECLARE done INT DEFAULT 0;

  DECLARE buscar VARCHAR(50);
  DECLARE reemplazo VARCHAR(50);

  #PRIMERO OBTENGO LAS SUBGERENCIAS
  DECLARE cur CURSOR FOR SELECT REPLACE(REPLACE(indicador,'CONSUMO_SUBINVENTARIO_',''),'_SUBGERENCIA',''), valor from INDICADOR WHERE indicador LIKE "CONSUMO_SUBINVENTARIO_%_SUBGERENCIA" AND area = area_aux;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
  
  OPEN cur;
  read_loop: LOOP
 
    FETCH cur INTO buscar,reemplazo; 
    IF done THEN
        LEAVE read_loop;  
    END IF;
    
    call REEMPLAZAR_CONSUMOS_SUBGERENCIAS(buscar,reemplazo,area_aux);
 
  END LOOP;
  CLOSE cur; 

END //


DELIMITER ;