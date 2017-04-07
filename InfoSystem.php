<?php
session_start();

include('DataSystem.php');
header('Content-type: text/html; charset=UTF-8');
class InfoSystem extends DataSystem
{
    private $bConnection;
    private $strUspName;
	private $iLimit;
	//private $strDataBase = DataSystem::dbName;
    
	
    private function OpenDB()
    {
        $bConnection = new mysqli(DataSystem::dbHost, DataSystem::dbUser, DataSystem::dbPwd, DataSystem::dbName);
		if ($bConnection->connect_errno) 
		{
			//echo 'NOK: (Error: '.$mysqli->connect_errno.') '.$mysqli->connect_error;
			$this->bConnection = false;
		}
		else
		{
			//echo 'OK '.$mysqli->host_info.'\n';
			$this->bConnection = $bConnection;
		}
		$this->iLimit = 90;
		
    }
    
    
    private function CloseDB()
    {
        //mysql_close($this->bConnection);
    }
    
    public function getAccess($strUser, $strPwd)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT CONCAT(Users.Email,':',Users.IdProfile,':',Users.User) AS L1 FROM Users WHERE Users.Email='".$strUser."' AND Users.Access='".$strPwd."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5, 6, 7) ");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
        $this->CloseDB();
        return $strR;
    }
	
	public function getIdUser($strUser)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT CONCAT(Users.IdUser,':',Users.IdProfile) AS L1 FROM Users WHERE Users.Email='".$strUser."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5,6,7) ");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
        $this->CloseDB();
        return $strR;
    }
	
	public function ValSche($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT IFNULL(Registers.Sche, 0) AS L1 FROM Registers WHERE Registers.Status = 1 AND Registers.IdRegister=".$p);
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
        $this->CloseDB();
        return $strR;
    }
	
	public function setPEMproj2($strJson)
    {
		return $strJson;
        /*$this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
        $this->CloseDB();
        return $strR;*/
    }
	
	public function setPEMproj($strJson, $bFlag)
	{
		
		$a = json_decode($strJson, true);
		/*
		$str = json_encode($a);
		$qry = "INSERT INTO Registers (IdUser, IdTown, IdAcademicGrade, SchoolName, ProjName, HeadProblem, ProjGoal, Mn, Fn, Tn, ProjectType, IdProblem ) VALUES (".$inserted_id.",".$a['Town'].",". $a['Grade'].",'". $a['School']."','".$a['ProjName']."','".$a['Problem']."','".$a['Goal']."',".$a['Male'].",".$a['Female'].",".$a['Total'].", ".$a['Profile'].", ".$a['IdProblem'].");";
		$strR = 'hola desde php2: '.$qry.', '.$str;//.mb_convert_encoding($str, 'HTML-ENTITIES','UTF-8');
		*/
		$this->OpenDB();
        $this->bConnection->set_charset('utf8');
		
		$res = $this->bConnection->query("SELECT COUNT(Users.IdUser) AS L1 FROM Users WHERE Users.Email='".$a['Mail']."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5,6, 7) ");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
		
		if($strR == 0)//nuevo
		{

			$res = $this->bConnection->query("SELECT HexColors.IdHexColor AS L1, HexColors.Uses AS L2 FROM HexColors WHERE HexColors.Status=1 ORDER BY HexColors.Uses ASC LIMIT 1");
			$row = $res->fetch_assoc();
			$Color = $row['L1'];
			
			$iAm = intval($row['L2']) + 1;
			
			$res = $this->bConnection->query("UPDATE HexColors SET Uses = ".$iAm." WHERE HexColors.IdHexColor = ".$Color );
		
		//	$res = $this->bConnection->query("INSERT INTO Users (User, Email, Access, Code, IdProfile, Tel, IdHexColor) VALUES ('".utf8_decode($a['Name'])."','".$a['Mail']."','".rand(1111,9999)."','".explode('@',$a['Mail'])[0]."',1, '".$a['Tel']."',".$Color.");");
			$res = $this->bConnection->query("INSERT INTO Users (User, Email, Access, Code, IdProfile, Tel, IdHexColor) VALUES ('".$a['Name']."','".$a['Mail']."','".rand(1111,9999)."','".explode('@',$a['Mail'])[0]."',1, '".$a['Tel']."',".$Color.");");
			$inserted_id = $this->bConnection->insert_id;
		//	$res = $this->bConnection->query("INSERT INTO Registers (IdUser, IdTown, Grade, SchoolName, ProjName, HeadProblem, ProjGoal, Mn, Fn, Tn, ProjectType, IdSubProblem, IdAcademicLevel, Student, IdManager ) VALUES (".$inserted_id.",".$a['Town'].",'". $a['Grade']."','".utf8_decode( $a['School'])."','".utf8_decode($a['ProjName'])."','".utf8_decode($a['Problem'])."','".utf8_decode($a['Goal'])."',".$a['Male'].",".$a['Female'].",".$a['Total'].", ".$a['Profile'].", ".$a['IdProblem'].", ".$a['Level'].", ".$a['Student'].", ".$a['Manager'].");");
			$res = $this->bConnection->query("INSERT INTO Registers (IdUser, IdTown, Grade, SchoolName, ProjName, HeadProblem, ProjGoal, Mn, Fn, Tn, ProjectType, IdSubProblem, IdAcademicLevel, Student, IdManager ) VALUES (".$inserted_id.",".$a['Town'].",'". $a['Grade']."','".$a['School']."','".$a['ProjName']."','".$a['Problem']."','".$a['Goal']."',".$a['Male'].",".$a['Female'].",".$a['Total'].", ".$a['Profile'].", ".$a['IdProblem'].", ".$a['Level'].", ".$a['Student'].", ".$a['Manager'].");");
		
			$res = $this->bConnection->query("SELECT Users.Access AS L1 FROM Users WHERE Users.IdUser='".$inserted_id."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5,6,7) ");
			$row = $res->fetch_assoc();
			//$strR = 'Nuevo: '.$row['L1'];
			$strR = $row['L1'];
		
		}
		else if($strR == 1 && $bFlag != true) // ya existe
		{
			$res = $this->bConnection->query("SELECT Users.IdUser AS L1 FROM Users WHERE Users.Email='".$a['Mail']."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5,6) ");
			$row = $res->fetch_assoc();
			$strR = $row['L1'];
		//	$res = $this->bConnection->query("INSERT INTO Registers (IdUser, IdTown, Grade, SchoolName, ProjName, HeadProblem, ProjGoal, Mn, Fn, Tn, ProjectType, IdSubProblem, IdAcademicLevel, Student, IdManager ) VALUES (".$strR.",".$a['Town'].",'". $a['Grade']."','".utf8_decode($a['School'])."','".utf8_decode($a['ProjName'])."','".utf8_decode($a['Problem'])."','".utf8_decode($a['Goal'])."',".$a['Male'].",".$a['Female'].",".$a['Total'].", ".$a['Profile'].", ".$a['IdProblem'].", ".$a['Level'].", ".$a['Student'].", ".$a['Manager'].");");
			$res = $this->bConnection->query("INSERT INTO Registers (IdUser, IdTown, Grade, SchoolName, ProjName, HeadProblem, ProjGoal, Mn, Fn, Tn, ProjectType, IdSubProblem, IdAcademicLevel, Student, IdManager ) VALUES (".$strR.",".$a['Town'].",'". $a['Grade']."','".$a['School']."','".$a['ProjName']."','".$a['Problem']."','".$a['Goal']."',".$a['Male'].",".$a['Female'].",".$a['Total'].", ".$a['Profile'].", ".$a['IdProblem'].", ".$a['Level'].", ".$a['Student'].", ".$a['Manager'].");");
			//$strR = 'Listo';
			$res = $this->bConnection->query("SELECT Users.Access AS L1 FROM Users WHERE Users.IdUser='".$strR."' AND Users.Status = 1 AND Users.IdProfile IN (1,2,5,6) ");
			$row = $res->fetch_assoc();
			//$strR = 'Ya existe: '.$row['L1'];
			$strR = $row['L1'];
			//$strR = $res;
		}
		else if($strR == 1 && $bFlag == true)// button->register el correo ya esta ocupado
		{
			$strR = 'NOK';
		}
		else
		{
			$strR = 'No hay caso';
		}
		
		
		$this->CloseDB();
        return $strR;
	}
	
	/*
	
	public function SetSchedule($strArg1, $strArg2, $strArg3, $strArg4)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf-8');
        $res = $this->bConnection->query("INSERT INTO Schedules (IdTeacher, IdSchoolarSeason, IdClass, GroupClass) VALUES (".$strArg1.",".$strArg2.",".$strArg3.",'".strtoupper($strArg4)."');");
      
        $this->CloseDB();
        return $res;
		//return "INSERT INTO Schedules (IdTeacher, IdSchoolarSeason, IdClass, GroupClass, Status) VALUES (".$strArg1.",".$strArg2.",".$strArg3.",'".$strArg4."',1);";
    }
	
	*/
	
	
	
	public function setSchedules($iIdEvent, $strStart, $strEnd, $strText, $iId, $iIdProfile, $s, $p, $arrtype)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');

		if($iIdProfile == 2 || $iIdProfile == 6 || $iIdProfile == 7)
		{

			$res = $this->bConnection->query("SELECT Schedules.Text AS L1 FROM Schedules WHERE Schedules.Status = 1 AND Schedules.IdEvent =".$iIdEvent);
			$row = $res->fetch_assoc();
			$R = $row['L1'];

			if(strcmp($strText,'@#@') != 0)
			{
				
				$str = $R.'<-->'.$_SESSION['nme'].'@'.$strText;
			}
			else
			{
				$str = $R.' ';
			}


				if(strlen($R) > 0)
				{

					$res = $this->bConnection->query("UPDATE Schedules SET Text = '".$str."', Start = '".$strStart."', End = '".$strEnd."', IdLayerStruct = ".$s.", IdScheDetail = ".$arrtype."  WHERE Schedules.IdEvent =".$iIdEvent.";");
					//$res = $this->bConnection->query("UPDATE Schedules SET Text = '".$str."', Start = '".$strStart."', End = '".$strEnd."', IdEventType = '".$iEventType."'  WHERE Schedules.IdEvent =".$iIdEvent.";");
					$str = 'Update: '.$str;
				} 
				else
				{
					$res = $this->bConnection->query("INSERT INTO Schedules (IdEvent, Start, End, Text, IdUser, IdLayerStruct, IdRegister, IdEventType, IdScheDetail) VALUES (".$iIdEvent.", '".$strStart."','".$strEnd."','".$strText."',".$iId.", ".$s.", ".$p.", 2, ".$arrtype.");");
					$str = 'Admin: '.$str;
				}

			
		}
		else if($iIdProfile == 1 || $iIdProfile == 5)
		{
			$res = $this->bConnection->query("SELECT COUNT(Schedules.IdSchedule) AS L1 FROM Schedules WHERE Schedules.Status = 1 AND Schedules.IdEvent =".$iIdEvent);
			$row = $res->fetch_assoc();
			$R = $row['L1'];
			if($R > 0)
			{
				$str = $this->bConnection->query('UPDATE Schedules SET Status = 2 WHERE Schedules.IdEvent ='.$iIdEvent);
				
			}
			
			$str = $this->bConnection->query("INSERT INTO Schedules (IdEvent, Start, End, Text, IdUser, IdLayerStruct, IdRegister, IdEventType, IdScheDetail) VALUES (".$iIdEvent.", '".$strStart."','".$strEnd."','".$strText."',".$iId.", ".$s.", ".$p.", 2, ".$arrtype.");");

			$str = 'PEM IdSche:'.$R.', Text: '.$strText.', Event: '.$iIdEvent;
		}
		else
		{
			$str = 'No hay caso';
		}
		
		$this->CloseDB();
        return $str;
	//	return $iIdEvent.', '.$strStart.', '.$strEnd.', '.$strText.', '.$iId.', '.$iIdProfile.', '.$s.', '.$p;
		//return "INSERT INTO Schedules (IdEvent, Start, End, Text, IdUser, IdLayerStruct, IdRegister, IdEventType) VALUES (".$iIdEvent.", '".$strStart."','".$strEnd."','".$strText."',".$iId.", ".$s.", ".$p.", 2);";
    }
	
	public function setSchedule($iIdEvent, $strStart, $strEnd, $strText, $iId, $iIdProfile, $iEventType)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');

		if($iIdProfile == 2 || $iIdProfile == 6 || $iIdProfile == 7)
		{

			$res = $this->bConnection->query("SELECT Schedules.Text AS L1 FROM Schedules WHERE Schedules.Status = 1 AND Schedules.IdEvent =".$iIdEvent);
			$row = $res->fetch_assoc();
			$R = $row['L1'];
			
			
			//$str = 'Admin IdSche:'.$R.', Text: '.$strText.', Event: '.$iIdEvent;
			//$str = $R.', '. $strText;
			
			
			if(strcmp($strText,'@#@') != 0)
			{
				
				$str = $R.'<-->'.$_SESSION['nme'].'@'.$strText;
			}
			else
			{
				$str = $R.' ';
			}

			
			
			
			/*if(strcmp($str, $strText) != 0)
			{*/
				
				
				if(strlen($R) > 0)
				{

					$res = $this->bConnection->query("UPDATE Schedules SET Text = '".$str."', Start = '".$strStart."', End = '".$strEnd."'  WHERE Schedules.IdEvent =".$iIdEvent.";");
					//$res = $this->bConnection->query("UPDATE Schedules SET Text = '".$str."', Start = '".$strStart."', End = '".$strEnd."', IdEventType = '".$iEventType."'  WHERE Schedules.IdEvent =".$iIdEvent.";");
					$str = 'Update: '.$str;
				} 
				else
				{
					$res = $this->bConnection->query("INSERT INTO Schedules (IdEvent, Start, End, Text, IdUser, IdEventType) VALUES (".$iIdEvent.", '".$strStart."','".$strEnd."','".$strText."',".$iId.", ".$iEventType.");");
					$str = 'Admin: '.$str;
				}
			/*}
			else
			{
				
			}*/
			
		}
		else if($iIdProfile == 1 || $iIdProfile == 5)
		{
			$res = $this->bConnection->query("SELECT COUNT(Schedules.IdSchedule) AS L1 FROM Schedules WHERE Schedules.Status = 1 AND Schedules.IdEvent =".$iIdEvent);
			$row = $res->fetch_assoc();
			$R = $row['L1'];
			if($R > 0)
			{
				$str = $this->bConnection->query('UPDATE Schedules SET Status = 2 WHERE Schedules.IdEvent ='.$iIdEvent);
				
			}
			
			$str = $this->bConnection->query("INSERT INTO Schedules (IdEvent, Start, End, Text, IdUser, IdEventType) VALUES (".$iIdEvent.", '".$strStart."','".$strEnd."','".$strText."',".$iId.", ".$iEventType.");");

			$str = 'PEM IdSche:'.$R.', Text: '.$strText.', Event: '.$iIdEvent;
		}
		else
		{
			$str = 'No hay caso';
		}
		
		$this->CloseDB();
        return $str;
		//return $iIdEvent.', '.$strStart.', '.$strEnd.', '.$strText.', '.$iId.', '.$iIdProfile;
    }
	
	public function deleteSchedule($iIdEvent, $iId)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("UPDATE Schedules SET Status = 2, IdUserEdit=".$iId." WHERE Schedules.IdEvent = ".$iIdEvent." AND Schedules.Status = 1;");
        $this->CloseDB();
        return $strR;
    }
	
	public function deleteEvi($iHash)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("UPDATE DataUpload SET Status = 2 WHERE Hash = '".$iHash."'");
        $this->CloseDB();
        return $res.'@'.$iHash;
		//return "UPDATE DataUpload SET Status = 2 WHERE Hash = '".$iHash."'";
    }
	
	
	public function getSchedule($Id, $Profile, $p)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		
		if($Profile == 1 || $Profile == 5)
		{
			
			//colores por usuario
			//$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, Schedules.Text AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1 AND Users.IdUser=".$Id);
			
			//colores por componente
			
			$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(LayersStruct.Code,' ',ScheDetails.ScheDetail) AS text, CONCAT('#',LayersStruct.BgColor) AS color, '#ffffff' AS textColor, Schedules.IdScheDetail AS arrtype, LayersStruct.IdLayerStruct AS subject FROM Schedules INNER JOIN ScheDetails ON Schedules.IdScheDetail = ScheDetails.IdScheDetail INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN EventsType ON Schedules.IdEventType = EventsType.IdEventType INNER JOIN HexColors ON EventsType.IdHexColor = HexColors.IdHexColor INNER JOIN LayersStruct ON Schedules.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND Schedules.Status = 1 AND Users.Status = 1 AND ScheDetails.Status = 1 AND HexColors.Status=1 AND Users.IdUser=".$Id." AND Schedules.IdRegister = ".$p);
			//$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(LayersStruct.Code,' ',Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN EventsType ON Schedules.IdEventType = EventsType.IdEventType INNER JOIN HexColors ON EventsType.IdHexColor = HexColors.IdHexColor INNER JOIN LayersStruct ON Schedules.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1 AND Users.IdUser=".$Id." AND Schedules.IdRegister = ".$p);
			
		}
		else if($Profile == 2 || $Profile == 6 || $Profile == 7)
		{
			//colores por usuario
			//con ubactividades
			//$res = $this->bConnection->query("SELECT SubActivities.IdEvent AS id, SubActivities.Date AS start_date, SubActivities.Date AS end_date, CONCAT(Users.User, '@',SubActivities.Val) AS text, HexColors.Color AS color,  '#ffffff' AS textColor FROM SubActivities INNER JOIN Registers ON SubActivities.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE SubActivities.Status = 1 UNION SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1");
			//sin subactividades
			//$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1");
			
	
			//colores por componente
			$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,LayersStruct.Code,' ',ScheDetails.ScheDetail) AS text, CONCAT('#',LayersStruct.BgColor) AS color, '#ffffff' AS textColor, Schedules.IdScheDetail AS arrtype, LayersStruct.IdLayerStruct AS subject FROM Schedules INNER JOIN ScheDetails ON Schedules.IdScheDetail = ScheDetails.IdScheDetail INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN EventsType ON Schedules.IdEventType = EventsType.IdEventType INNER JOIN HexColors ON EventsType.IdHexColor = HexColors.IdHexColor INNER JOIN LayersStruct ON Schedules.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND Schedules.Status = 1 AND Users.Status = 1 AND ScheDetails.Status = 1 AND HexColors.Status=1 AND Schedules.IdRegister = ".$p);
		}
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return $res;
	}
	
	public function getStates()
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona una entidad...' AS Name UNION SELECT LocStates.IdLocState AS Code, LocStates.Name AS Name FROM LocStates WHERE LocStates.Status=1 ORDER BY Code ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getCounties($strArg1)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un municipio...' AS Name UNION SELECT LocCounties.IdLocCounty AS Code, LocCounties.Name AS Name FROM LocCounties WHERE LocCounties.Status = 1 AND LocCounties.IdLocState = ".$strArg1." ORDER BY Code ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getTowns($strArg1)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona una localidad...' AS Name UNION SELECT LocTowns.IdLocTown AS Code, LocTowns.Name AS Name FROM LocTowns WHERE LocTowns.Status = 1 AND LocTowns.IdLocCounty = ".$strArg1." ORDER BY Code ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getLevels()
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona...' AS Name UNION SELECT AcademicLevels.IdAcademicLevel AS Code, AcademicLevels.Level AS Name FROM AcademicLevels WHERE AcademicLevels.Status = 1 ORDER BY Code ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getGrades($strArg1)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona...' AS Name UNION SELECT AcademicGrades.IdAcademicGrade AS Code, AcademicGrades.Grade AS Name FROM AcademicGrades WHERE AcademicGrades.Status = 1 AND AcademicGrades.IdAcademicLevel = ".$strArg1);
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getProfiles($strArg1)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT Profiles.IdProfile AS Code, Profiles.Profile AS Name FROM Profiles WHERE Profiles.Status = 1 AND Profiles.ServiceStatus = ".$strArg1);
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	/*public function getSubProblems()
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona una línea de acción ...' AS Name UNION SELECT SubProblems.IdSubProblem AS Code, SubProblems.SubProblem AS Name FROM SubProblems WHERE SubProblems.Status = 1");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}*/
	
	public function getSubProblems($id)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona ...' AS Name UNION SELECT SubProblems.IdSubProblem AS Code, SubProblems.SubProblem AS Name FROM SubProblems WHERE SubProblems.Status = 1 AND SubProblems.IdProblem = ".$id);
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getProblems()
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona una línea de acción ...' AS Name UNION SELECT Problems.IdProblem AS Code, Problems.Problem AS Name FROM Problems WHERE Problems.Status = 1");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getProjects()
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		///////////////////////////////7
		$res = $this->bConnection->query("SELECT Users.IdUser AS L1 FROM Users WHERE Users.User = '".$_SESSION['nme']."' AND Users.Status = 1");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
		
		/////////////////////
		
		
		if($_SESSION['pfl'] == 2){
			$res = $this->bConnection->query("SELECT Registers.SchoolName AS School, Registers.ProjName AS Project, Users.User AS Name, Registers.HeadProblem AS Problem, Registers.ProjGoal AS Goal, Registers.IdRegister AS No, CONCAT(AcademicLevels.Level, ', ', Registers.Grade) AS Gr FROM Registers INNER JOIN Users ON Registers.IdUser = Users.Iduser INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE AcademicLevels.Status = 1 AND Registers.Status = 1 AND Users.Status = 1 AND Profiles.IdProfile IN (1,5)");
		}
		else if($_SESSION['pfl'] == 6){
			$res = $this->bConnection->query("SELECT Registers.SchoolName AS School, Registers.ProjName AS Project, Users.User AS Name, Registers.HeadProblem AS Problem, Registers.ProjGoal AS Goal, Registers.IdRegister AS No, CONCAT(AcademicLevels.Level, ', ', Registers.Grade) AS Gr FROM Registers INNER JOIN Users ON Registers.IdUser = Users.Iduser INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE AcademicLevels.Status = 1 AND Registers.IdManager = ".$strR." AND Registers.Status = 1 AND Users.Status = 1 AND Profiles.IdProfile IN (1,5)");
		}
		else if($_SESSION['pfl'] == 7){
			$res = $this->bConnection->query("SELECT Registers.SchoolName AS School, Registers.ProjName AS Project, Users.User AS Name, Registers.HeadProblem AS Problem, Registers.ProjGoal AS Goal, Registers.IdRegister AS No, CONCAT(AcademicLevels.Level, ', ', Registers.Grade) AS Gr FROM Registers INNER JOIN Users ON Registers.IdUser = Users.Iduser INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE AcademicLevels.Status = 1 AND Registers.IdManager = ".$strR." AND Registers.Status = 1 AND Users.Status = 1 AND Profiles.IdProfile IN (1,5)");
		}
		else{
			$res = $this->bConnection->query("SELECT Registers.SchoolName AS School, Registers.ProjName AS Project, Users.User AS Name, Registers.HeadProblem AS Problem, Registers.ProjGoal AS Goal, Registers.IdRegister AS No, CONCAT(AcademicLevels.Level, ', ', Registers.Grade) AS Gr FROM Registers INNER JOIN Users ON Registers.IdUser = Users.Iduser INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE AcademicLevels.Status = 1 AND Users.User = '".$_SESSION['nme']."' AND Registers.Status = 1 AND Users.Status = 1 AND Profiles.IdProfile IN (1,5)");
		}
		
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	
		//return "---- ".$strR."---SELECT Users.IdUser AS L1 FROM Users WHERE Users.User = '".$_SESSION['nme']."' AND Users.Status = 1--------"."SELECT Registers.SchoolName AS School, Registers.ProjName AS Project, Users.User AS Name, Registers.HeadProblem AS Problem, Registers.ProjGoal AS Goal, Registers.IdRegister AS No FROM Registers INNER JOIN Users ON Registers.IdUser = Users.Iduser INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile WHERE Registers.IdManager = ".$strR." AND Registers.Status = 1 AND Users.Status = 1 AND Profiles.IdProfile IN (1,5)";
	}
	
	public function getMethodsTree()
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT LayersMethods.IdLayerMethod AS Code, LayersMethods.Layer AS Name FROM LayersMethods WHERE LayersMethods.Status = 1 AND LayersMethods.IdLayerMethod IN (5,6) ORDER BY LayersMethods.IdLayerMethod DESC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	//
	
	public function getProjectsList()
	{
		
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		///////////////////////////////7
		$res = $this->bConnection->query("SELECT Users.IdUser AS L1 FROM Users WHERE Users.User = '".$_SESSION['nme']."' AND Users.Status = 1");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
		
		/////////////////////
		
		if($_SESSION['pfl'] == 2)
		{
			$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, CONCAT(Registers.ProjName, ' - ', Users.User, ' - Escuela: ' , Registers.SchoolName) AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1;");
		}
		else if($_SESSION['pfl'] == 6)
		{
			$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, CONCAT(Registers.ProjName, ' - ', Users.User, ' - Escuela: ' , Registers.SchoolName) AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Registers.IdManager =".$strR);
		}
		else if($_SESSION['pfl'] == 7)
		{
			$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, CONCAT(Registers.ProjName, ' - ', Users.User, ' - Escuela: ' , Registers.SchoolName) AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser INNER JOIN DataUpload ON Registers.IdRegister = DataUpload.IdRegister WHERE Registers.Status = 1 AND DataUpload.Status = 1 AND Users.Status = 1 AND DataUpload.Rev2  IS NULL AND DataUpload.Rev1 IS NOT NULL;");
		}
		else
		{
			$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, Concat(Registers.ProjName, ' - ' , Registers.SchoolName) AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Users.User = '".trim($_SESSION['nme'])."'");
		}
		
		
		
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			//$rows[] = array_map('utf8_encode', $r);
			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return "SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, Registers.ProjName AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Users.User = '".trim($_SESSION['nme'])."'";
		//return "SELECT '0' AS Code, 'Selecciona un proyecto...' AS Name UNION SELECT Registers.IdRegister AS Code, Registers.ProjName AS Name FROM Registers INNER JOIN Users  ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Users.User = '".$_SESSION['nme']."'";
	}
	
	public function getValTreeEmpty($iTree)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT Registers.IdRegister AS L1, LayersStruct.Code AS L2, ' ' AS L3, 'FFFFFF' AS L4 FROM Registers INNER JOIN TreeStructure ON Registers.IdRegister = TreeStructure.IdRegister INNER JOIN LayersStruct ON TreeStructure.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND TreeStructure.Status = 1 AND Registers.Status = 1 AND TreeStructure.Tree =5");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return 'hola desde php 1: '.$iProject;
	}
	//SELECT Registers.IdRegister AS L1, LayersStruct.Code AS L2, ' ' AS L3, LayersStruct.BgColor AS L4 FROM Registers INNER JOIN TreeStructure ON Registers.IdRegister = TreeStructure.IdRegister INNER JOIN LayersStruct ON TreeStructure.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND TreeStructure.Status = 1 AND Registers.Status = 1 /*AND TreeStructure.IdRegister = 40*/ AND TreeStructure.Tree =6
	
	public function getValTree($iProject, $iTree)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		//$res = $this->bConnection->query("SELECT Registers.IdRegister AS L1, LayersStruct.Code AS L2, TreeStructure.Val AS L3, LayersStruct.BgColor AS L4 FROM Registers INNER JOIN TreeStructure ON Registers.IdRegister = TreeStructure.IdRegister INNER JOIN LayersStruct ON TreeStructure.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND TreeStructure.Status = 1 AND Registers.Status = 1 AND TreeStructure.IdRegister = ".$iProject." AND TreeStructure.Tree =".$iTree);
		$res  =$this->bConnection->query("SELECT IFNULL(t1.L1,0) AS L1, LayersStruct.Code AS L2, IFNULL(t1.L3,' ') AS L3, IFNULL(t1.L4,'ffffff') AS L4, IFNULL(t1.L5, 'BFBFBF') AS L5 FROM (SELECT Registers.IdRegister AS L1, LayersStruct.Code AS L2, TreeStructure.Val AS L3, LayersStruct.BgColor AS L4, LayersStruct.txtColor AS L5 FROM Registers INNER JOIN TreeStructure ON Registers.IdRegister = TreeStructure.IdRegister INNER JOIN LayersStruct ON TreeStructure.IdLayerStruct = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND TreeStructure.Status = 1 AND Registers.Status = 1 AND TreeStructure.IdRegister = ".$iProject." AND LayersStruct.IdLayerMethod != 8 AND TreeStructure.Tree = ".$iTree.") AS t1 RIGHT JOIN LayersStruct ON t1.L2 = LayersStruct.Code WHERE LayersStruct.IdLayerMethod != 8" );
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return 'hola desde php 1: '.$iProject;
	}
	
	public function setValTree($strJson,$iTree)
	{
		$arrA = json_decode($strJson, true);
		//$R = '5';
		$this->OpenDB();
        $this->bConnection->set_charset('utf8');
		
			
			$res = $this->bConnection->query("SELECT LayersStruct.IdLayerStruct AS L1 FROM LayersStruct WHERE LayersStruct.Code ='".$arrA['a']."'");
			$row = $res->fetch_assoc();
			
			$R = $row['L1'];
			
			
			$res = $this->bConnection->query("UPDATE TreeStructure SET Status = 2 WHERE TreeStructure.IdRegister = ".$arrA['s']." AND TreeStructure.IdLayerStruct = ".$R." AND TreeStructure.Tree = ".$iTree.";");
			$res = $this->bConnection->query("INSERT INTO TreeStructure (IdRegister, Val, IdLayerStruct, Tree) VALUES (".$arrA['s'].",'".$arrA['q']."',".$R.", ".$iTree.");");
		
		$this->CloseDB();
        return $res;
		//return "INSERT INTO TreeStructure (IdRegister, Val, IdLayerStruct, Tree) VALUES (".$arrA['s'].",'".$arrA['q']."',".$R.", ".$iTree.");--------------------UPDATE TreeStructure SET Status = 2 WHERE TreeStructure.IdRegister = ".$arrA['s']." AND TreeStructure.IdLayerStruct = ".$R." AND TreeStructure.Tree = ".$iTree;

	}
	
	
	public function getProjectElement($strArg1, $strArg2, $strArg3)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT TreeStructure.Val AS L1 FROM TreeStructure INNER JOIN LayersStruct ON TreeStructure.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN Registers ON TreeStructure.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND TreeStructure.Status = 1 AND Users.Status = 1 AND LayersStruct.Status = 1 AND Registers.IdRegister= ".$strArg2." AND LayersStruct.Code = '".$strArg1."' AND TreeStructure.Tree =".$strArg3);
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
        $this->CloseDB();
        return $strR;
    }
	
	
	public function getSubActivities($iIdRegister, $iIdLayerMethod)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT SubActivities.IdSubActivity AS Code, CONCAT(SubActivities.Val, '@', SubActivities.Date) AS Name FROM SubActivities WHERE SubActivities.Status = 1 AND SubActivities.IdRegister = ".$iIdRegister." AND SubActivities.IdLayerMethod = ".$iIdLayerMethod.";");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function setSubActivities($iIdRegister, $iIdLayerMethod, $strText, $strDate)
	{
		$h = "desde INSERT INTO SubActivities (Val, IdLayerMethod, IdRegister, Date) VALUES ('".$strText."', ".$iIdLayerMethod.", ".$iIdRegister.", '".$strDate."');";
		$this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("INSERT INTO SubActivities (Val, IdLayerMethod, IdRegister, Date, IdEvent) VALUES ('".$strText."', ".$iIdLayerMethod.", ".$iIdRegister.", '".$strDate."', '".hash('ripemd160',$h).":ed');");
        
        $this->CloseDB();
        return $res;
		//return "desde INSERT INTO SubActivities (Val, IdLayerMethod, IdRegister, Date) VALUES ('".$strText."', ".$iIdLayerMethod.", ".$iIdRegister.", '".$strDate."');";
	}
	
	public function getLayersStruct($bSelector, $p)
	{
		
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		//$iLimit = 95;
		

		
				if($bSelector == 4)
				{
					$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct INNER JOIN DataUpload ON t2.IdRegister = DataUpload.IdRegister AND t2.Ind = DataUpload.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataUpload ON t1.IdRegister = DataUpload.IdRegister AND t1.IdLayerStruct = DataUpload.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 ORDER BY L1 ASC;");
				}
				else if($bSelector == 3)
				{
					$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN (SELECT t4.L6, t4.L4 AS L1, t4.L5 AS L2 FROM (SELECT t3.L1, t3.L6, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, t2.L5, LEFT(LayersStruct.Code,2) AS L6 FROM (SELECT t6.IdRegister AS L1, IFNULL(t5.L5,0) AS L5, t6.Ind AS L2, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.Ind FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataMIR.Ind != 0) AS t6 ON t5.L2 = t6.Ind) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 ) AS t3 GROUP BY L1,L6) AS t4 WHERE (L5*100/L4) > ".$this->iLimit.") AS t5 ON LayersStruct.Code = t5.L6 WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC;");
					//$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN (SELECT t4.*, (t4.L1 - t4.L2) FROM (SELECT SUM(t3.L1) AS L1, SUM(t3.L2) AS L2, t3.L6 AS L6 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataUpload.Hash AS L5, LEFT(LayersStruct.Code,2) AS L6 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1  AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0) AS t3 GROUP BY L6) AS t4 WHERE  ((t4.L2 * 100)/t4.L1) > ".$this->iLimit.") AS t5 ON LayersStruct.Code = t5.L6 WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC;");
					/*if($pro < 85)
					{
						//sin Fin, Pro, C1, C2, C3, C4
						$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." ORDER BY L1 ASC");
					}
					else
					{
						//con Fin, Pro, C1, C2, C3, C4
						$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT CONCAT(LayersStruct.Code,':',0) AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct inner join DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC");
					}*/
				}
				else if($bSelector == 1)
				{
					$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct inner join DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC");
				}
				else if($bSelector == 5)
				{
					$res = $this->bConnection->query("SELECT 'Selecciona tu indicador...' AS Name, 'L1' AS L1, 'L2' AS L2, 'L3' AS L3, 'L5' AS L5, 0 AS Code, 'Today' AS Today, 'Frec' AS LF, 'Diff' AS L9 UNION SELECT CONCAT(t1.Code,' - ', t1.L0, ' @ inicia ', t1.sStart, ' termina ' , t1.sEnd) AS Name, t1.Text AS L1, t1.sStart AS L2, t1.sEnd AS L3, t1.IdLayerStruct AS L5, CONCAT(t1.Code,':',IFNULL(t1.iSche,'0')) AS Code, STR_TO_DATE(NOW(),'%Y-%m-%d') AS Today, t1.LF, DATEDIFF(NOW(), t1.sEnd) AS L9 FROM (SELECT Schedules.Text, STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS sStart, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS sEnd, Schedules.IdLayerStruct, Schedules.IdRegister, DataMIR.Ind, LayersStruct.Struct AS LS, LayersStruct.Code AS Code, Schedules.IdSchedule AS iSche, DataMIR.NI AS L0, FrecMeasure.Frec AS LF FROM Schedules INNER JOIN DataMIR ON Schedules.IdLayerStruct = DataMIR.IdLayerStruct AND DataMIR.IdRegister = Schedules.IdRegister INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 UNION SELECT Schedules.Text, STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS sStart, STR_TO_DATE(Schedules.End,'%Y-%m-%d')  AS sEnd, Schedules.IdLayerStruct, Schedules.IdRegister, DataMIR.Ind, LayersStruct.Struct AS LS, LayersStruct.Code AS Code, Schedules.IdSchedule AS iSche, DataMIR.NI AS L0, FrecMeasure.Frec AS LF FROM Schedules INNER JOIN DataMIR ON Schedules.IdLayerStruct = DataMIR.Ind AND DataMIR.IdRegister = Schedules.IdRegister INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.Status = 1 AND Schedules.IdRegister = ".$p."  AND DataMIR.Status = 1 AND LayersStruct.Status = 1 AND DataMIR.IdRegister = ".$p." ) AS t1 WHERE DATEDIFF(t1.sEnd,NOW()) >=  -2 AND NOW() >= t1.sStart ;");
					
				}
				else if($bSelector == 6)
				{
					$res = $this->bConnection->query("SELECT 'L0' AS L0, 'L1' AS L1 , 'L3' AS L3, 'Selecciona tu indicador...' AS Name, 0 AS Code, 'LF' AS LF, 'L9' AS L9 UNION SELECT t2.L0, t2.L1, t2.L3, CONCAT(LayersStruct.Code, ' - ', t2.L2) AS Name, CONCAT(LayersStruct.Code, ':',t2.L4) AS Code, t2.L5 AS LF, DATEDIFF(NOW(), t2.L3) AS L9 FROM (SELECT t1.IdRegister AS L0,t1.IdLayerStruct AS L1, t1.NI AS L2, MIN(End) AS L3, t1.IdSchedule AS L4, t1.Frec AS L5 FROM (SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." UNION SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.Ind INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 WHERE DATEDIFF(t1.End,NOW()) >=  -2 AND NOW() >= t1.Start GROUP BY IdRegister, IdLayerStruct,NI) AS t2 INNER JOIN LayersStruct ON t2.L1 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1;");
					
				}
				else if($bSelector == 7)
				{
					$res = $this->bConnection->query("SELECT 'L0' AS L0, 'L1' AS L1 , 'L3' AS L3, 'Selecciona tu indicador...' AS Name, 0 AS Code, 'LF' AS LF, 'L9' AS L9, 'L6' AS L6 UNION SELECT t2.L0, t2.L1, t2.L3, CONCAT(LayersStruct.Code, ' - ', t2.L2) AS Name, CONCAT(LayersStruct.Code, ':',t2.L4) AS Code, t2.L5 AS LF, DATEDIFF(NOW(), t2.L3) AS L9, t2.L6 FROM (SELECT t1.IdRegister AS L0,t1.IdLayerStruct AS L1, t1.NI AS L2, MIN(End) AS L3, t1.IdSchedule AS L4, t1.Frec AS L5, t1.Timing AS L6 FROM (SELECT t61.* FROM (SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec, FrecMeasure.Timing FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t61 INNER JOIN (SELECT t4.L6, t4.L4 AS L1, t4.L5 AS L2, LayersStruct.IdLayerStruct AS L71 FROM (SELECT t3.L1, t3.L6, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, t2.L5, LEFT(LayersStruct.Code,2) AS L6 FROM (SELECT t6.IdRegister AS L1, IFNULL(t5.L5,0) AS L5, t6.Ind AS L2, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.Ind FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataMIR.Ind != 0) AS t6 ON t5.L2 = t6.Ind) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 ) AS t3 GROUP BY L1,L6) AS t4 INNER JOIN LayersStruct ON t4.L6 = LayersStruct.Code WHERE (L5*100/L4) > ".$this->iLimit." AND LayersStruct.Status = 1) AS t62 ON t61.IdLayerStruct = t62.L71 UNION SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec, FrecMeasure.Timing FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.Ind INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 WHERE DATEDIFF(t1.End,NOW()) >=  -2 GROUP BY IdRegister, IdLayerStruct,NI) AS t2 INNER JOIN LayersStruct ON t2.L1 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1;");
					//$res = $this->bConnection->query("SELECT t8.*, IFNULL(STR_TO_DATE(t9.End,'%Y-%m-%d'), 'Sin fecha') AS End FROM (SELECT t3.*, (t3.L9 + t3.L6), (t3.L9 + t3.L6) > t3.L6, (t3.L9 + t3.L6) >= 0 FROM (SELECT 'L0' AS L0, 'L1' AS L1 , 'L3' AS L3, 'Selecciona tu indicador...' AS Name, 0 AS Code, 'LF' AS LF, 'L9' AS L9, 'L6' AS L6 UNION SELECT t2.L0, t2.L1, t2.L3, CONCAT(LayersStruct.Code, ' - ', t2.L2) AS Name, CONCAT(LayersStruct.Code, ':',t2.L4) AS Code, t2.L5 AS LF, DATEDIFF(NOW(), t2.L3) AS L9, t2.L6 FROM (SELECT t1.IdRegister AS L0,t1.IdLayerStruct AS L1, t1.NI AS L2, MIN(End) AS L3, t1.IdSchedule AS L4, t1.Frec AS L5, t1.Timing AS L6 FROM (SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec, FrecMeasure.Timing FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." UNION SELECT STR_TO_DATE(Schedules.Start,'%Y-%m-%d') AS Start, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS End, Schedules.IdRegister, Schedules.IdLayerStruct, DataMIR.NI, Schedules.IdSchedule, FrecMeasure.Frec, FrecMeasure.Timing FROM Schedules INNER JOIN DataMIR ON Schedules.IdRegister = DataMIR.IdRegister AND Schedules.IdLayerStruct = DataMIR.Ind INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure WHERE Schedules.IdScheDetail = 2 AND FrecMeasure.Status = 1 AND Schedules.Status = 1 AND Schedules.IdRegister = ".$p." AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 WHERE DATEDIFF(t1.End,NOW()) >=  -2 /*AND NOW() >= t1.Start*/ GROUP BY IdRegister, IdLayerStruct,NI) AS t2 INNER JOIN LayersStruct ON t2.L1 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1) AS t3 WHERE (t3.L9 + t3.L6) >= 0) AS t8 LEFT JOIN (SELECT * FROM Schedules WHERE Schedules.Status = 1 AND Schedules.IdScheDetail = 1 AND Schedules.IdRegister = ".$p.") AS t9 ON t8.L1 = t9.IdLayerStruct ORDER BY Code;");
				}
				else
				{
					$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona tu indicador...' AS Name, 0 AS L1  UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT LayersStruct.Code AS Code, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS Name, LayersStruct.OrderMML AS L1 FROM LayersStruct inner join DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC");
				}

		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);

	}

	
	public function getFiles($iRegister)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS Code, DataUpload.Data AS Name FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$iRegister.";");

		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getFilesByLayer($iRegister, $iLayer)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS Code, DataUpload.Data AS Name, DataUpload.Name AS Ref FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$iRegister." AND LayersStruct.Code = '".$iLayer."' AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png');");

		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		
		$res2 = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS Code, DataUpload.Data AS Name, DataUpload.Name AS Ref FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$iRegister." AND LayersStruct.Code = '".$iLayer."' AND LayersStruct.Status = 1 AND DataUpload.Pathext NOT IN ('jpg','png');");

		$rows2 = array();
		while($r = mysqli_fetch_assoc($res2)) {
			$rows2[] = array_map('utf8_encode', $r);
		}
		$ar = array();
		$ar['IN'] = $rows;
		$ar['NIN'] = $rows2;
		
		$this->CloseDB();
		return json_encode($ar);
	}
	
	public function getComboFiles($strLayer)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS Code, DataUpload.Data AS Name FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataUpload.IdLayerStruct = ".$strLayer.";");
		
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function putFiles($strFileNameUser, $strFileName, $iLayerStruct, $iRegister, $Desc, $PR, $Ti, $iMethod, $strExt, $GyK, $iSch)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		

		$res = $this->bConnection->query("SELECT LayersStruct.IdLayerStruct AS L1 FROM LayersStruct WHERE LayersStruct.Code =  '".$iLayerStruct."' AND LayersStruct.Status = 1;");
		$row = $res->fetch_assoc();
		$iLayer = $row['L1'];
		

		$res = $this->bConnection->query("INSERT INTO DataUpload (Data, Name, IdLayerStruct, IdRegister, DE, PR, ActivityTitle, IdLayerMethod, Pathext, Hash, IdSchedule) VALUES ('".$strFileName."', '".$strFileNameUser."', ".$iLayer.", ".$iRegister.", '".str_replace("'"," ",$Desc)."', '".$PR."', '".$Ti."', ".$iMethod.", '".$strExt."', '".$GyK."', ".$iSch.");");
        
        $this->CloseDB();
        
		//$res = "INSERT INTO DataUpload (Data, Name, IdLayerStruct, IdRegister, DE, PR, ActivityTitle, IdLayerMethod) VALUES ('".$strFileName."', '".$strFileNameUser."', ".$iLayer.", ".$iRegister.", '".$Desc."', '".$PR."', '".$Ti."', ".$iMethod.");";
		return $res;
    }
	
	public function getLayerVal($strStruct, $iRegister)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT IFNULL(DataMIR.RN,'No hay dato') AS L1, IFNULL(DataMIR.ME,'No hay dato') AS L2, t1.Struct AS L3 FROM DataMIR INNER JOIN LayersStruct AS t1 ON (DataMIR.IdLayerStruct = t1.IdLayerStruct AND DataMIR.Ind = 0) OR (DataMIR.Ind = t1.IdLayerStruct AND DataMIR.Ind != 0)  WHERE DataMIR.Status = 1 AND t1.Status = 1 AND DataMIR.IdRegister = ".$iRegister." AND t1.Code = '".$strStruct."';");
        $row1 = $res->fetch_assoc();
       // $strR = $row1['L1'].":".$row1['L2'];
		
		
		$this->bConnection->set_charset('utf8');
        $res = $this->bConnection->query("SELECT Users.User AS L1, Users.Email AS L2 FROM Registers INNER JOIN Users ON Registers.IdManager = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Registers.IdRegister = ".$iRegister.";");
        $row2 = $res->fetch_assoc();
		$strR = $row1['L1'].":".$row1['L2'].":".$row2['L1'].":".$row2['L2'];
		
        $this->CloseDB();
        return $strR;
    }
	
	//
	public function getOrderMIR()
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT LayersStruct.IdLayerStruct AS L1, LayersStruct.Struct AS L2, LayersStruct.OrderMML AS L3, LayersStruct.IdLayerMethod AS L4, LayersStruct.CustomName AS L5  FROM LayersStruct WHERE LayersStruct.OrderMML != 0 AND LayersStruct.IdLayerMethod IN (1,2,3,4) ORDER BY LayersStruct.OrderMML ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getOrderMIRCombo()
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT * FROM (SELECT '0' AS Code, 'Selecciona el campo requerido...' AS Name, 0 AS L3 UNION SELECT LayersStruct.IdLayerStruct AS Code, LayersStruct.CustomName AS Name, LayersStruct.OrderMML AS L3  FROM LayersStruct WHERE LayersStruct.OrderMML != 0 AND LayersStruct.IdLayerMethod IN (3,4)) AS t1 ORDER BY t1.L3 ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	/*public function updateDataMIR()
	{
		$a = json_decode($json, true);
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("UPDATE Registers SET IdTown=".$a['Town']." ,SchoolName='".$a['School']."',ProjName='".$a['ProjName']."',HeadProblem='".$a['Problem']."',ProjGoal='".$a['Goal']."',Mn=".$a['Male'].",Fn=".$a['Female'].",Tn=".$a['Total'].",ProjectType=".$a['Profile'].", IdSubProblem=".$a['IdProblem'].",Grade='".$a['Grade']."',IdAcademicLevel=".$a['Level'].", Student=".$a['Student'].", IdManager=".$a['Manager']." WHERE IdRegister = ".$a['No'].";");
		$this->CloseDB();
		return $res;
	}*/
	
	public function setDataMIR($txta, $ni, $mc, $me, $fm, $di, $cr, $fv, $su, $p, $c, $ind, $um, $iRound)
	{
		//$rd = $txta.', '.$ni.', '.$mc.', '.$me.', '.$fm.', '.$di.', '.$cr.', '.$fv.', '.$su.', '.$p.', '.$c.', '.$ind.', '.$um;
		$this->OpenDB();
        $this->bConnection->set_charset('utf8');
		if($ind != 0)
		{

			$res = $this->bConnection->query("SELECT LayersStruct.Struct AS L1, LayersStruct.IdLayerStruct AS L2 FROM LayersStruct WHERE LayersStruct.Status = 1 AND (LayersStruct.IdLayerStruct = '".$c."' OR LayersStruct.Code='".$c."')");
			$row = $res->fetch_assoc();
			$strStruct = $row['L1'];
			$Ly = $row['L2'];
			
			$res = $this->bConnection->query("SELECT LayersStruct.IdLayerStruct AS L1 FROM LayersStruct WHERE LayersStruct.Status = 1 AND LayersStruct.Struct = '".$strStruct." Indicador ".$ind."'");
			$row = $res->fetch_assoc();
			$ind = $row['L1'];
		}
		else
		{

			$res = $this->bConnection->query("SELECT LayersStruct.Struct AS L1, LayersStruct.IdLayerStruct AS L2 FROM LayersStruct WHERE LayersStruct.Status = 1 AND (LayersStruct.IdLayerStruct = '".$c."' OR LayersStruct.Code='".$c."')");
			$row = $res->fetch_assoc();
			//$strStruct = $row['L1'];
			$Ly = $row['L2'];
		}

			$res = $this->bConnection->query("SELECT COUNT(IdDataMIR) AS L1 FROM DataMIR WHERE DataMIR.Status = 1 AND IdLayerStruct = ".$Ly." AND IdRegister = ".$p." AND Ind = ".$ind.";");
			$row = $res->fetch_assoc();
			$iNo = $row['L1'];
			$txtRs = '';
			
			/*if($iRound == 1)
			{
				$res = $this->bConnection->query("UPDATE DataMIR SET Status = 2 WHERE DataMIR.IdRegister = ".$p." AND DataMIR.IdLayerStruct = ".$Ly.";");
			}*/
			
			if($iNo > 0)
			{
				$res = $this->bConnection->query("UPDATE DataMIR SET RN = '".$txta."', NI = '".$ni."', MC = '".$mc."', ME = ".$me.", IdFrecMeasure = ".$fm.", IdUnitDimension = ".$di.", IdChainResult = ".$cr.", FV = '".$fv."', SU = '".$su."', Unit = '".$um."' WHERE IdLayerStruct = ".$Ly." AND IdRegister = ".$p." AND Ind = ".$ind.";");
				
				$txtRs =  "UPDATE DataMIR SET RN = '".$txta."', NI = '".$ni."', MC = '".$mc."', ME = ".$me.", IdFrecMeasure = ".$fm.", IdUnitDimension = ".$di.", IdChainResult = ".$cr.", FV = '".$fv."', SU = '".$su."', Unit = '".$um."' WHERE IdLayerStruct = ".$Ly." AND IdRegister = ".$p." AND Ind = ".$ind.";";
			}
			else
			{
				
				$res = $this->bConnection->query("INSERT INTO DataMIR (RN, NI, MC, ME, IdFrecMeasure, IdUnitDimension, IdChainResult, FV, SU, IdLayerStruct, IdRegister, Ind, Unit) VALUES ('".$txta."', '".$ni."', '".$mc."', ".$me.", ".$fm.", ".$di.", ".$cr.", '".$fv."', '".$su."', ".$Ly.", ".$p.", ".$ind.", '".$um."');");
				
				$txtRs = "INSERT INTO DataMIR (RN, NI, MC, ME, IdFrecMeasure, IdUnitDimension, IdChainResult, FV, SU, IdLayerStruct, IdRegister, Ind, Unit) VALUES ('".$txta."', '".$ni."', '".$mc."', ".$me.", ".$fm.", ".$di.", ".$cr.", '".$fv."', '".$su."', ".$Ly.", ".$p.", ".$ind.", '".$um."');";
				
		
			}
		
		/*
		
        $res = $this->bConnection->query("INSERT INTO DataMIR (RN, NI, MC, ME, IdFrecMeasure, IdUnitDimension, IdChainResult, FV, SU, IdLayerStruct, IdRegister, Ind, Unit) VALUES ('".$txta."', '".$ni."', '".$mc."', ".$me.", ".$fm.", ".$di.", ".$cr.", '".$fv."', '".$su."', ".$Ly.", ".$p.", ".$ind.", '".$um."');");
        */
		
		
        $this->CloseDB();
		return $txtRs;
   
	}
	
	public function getDataMIR($p)
	{
		
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		
		//SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = 40 AND LayersStruct.Status = 1 AND DataMIR.Ind != 0
		
		
		//SELECT t1.L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8 FROM (SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = 40 AND UnitsDimension.Status = 1 AND FrecMeasure.Status = 1 AND ChainResults.Status = 1 AND LayersStruct.Status = 1) AS t1 RIGHT JOIN LayersStruct ON t1.L9 = LayersStruct.IdLayerStruct WHERE LayersStruct.OrderMML != 0 ORDER BY L2
		
		//$res = $this->bConnection->query("SELECT t1.L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8 FROM (SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND UnitsDimension.Status = 1 AND FrecMeasure.Status = 1 AND ChainResults.Status = 1 AND LayersStruct.Status = 1 AND DataMIR.Ind = 0) AS t1 RIGHT JOIN LayersStruct ON t1.L9 = LayersStruct.IdLayerStruct WHERE LayersStruct.OrderMML != 0 ORDER BY L2");
		
		
		// ultima $res = $this->bConnection->query("SELECT t1.L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8 FROM (SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind = 0 UNION SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind != 0) AS t1 RIGHT JOIN LayersStruct ON t1.L9 = LayersStruct.IdLayerStruct WHERE LayersStruct.OrderMML != 0 ORDER BY L2;");
		
		
		/////////////////////////////////
		$res = $this->bConnection->query("SELECT LayersStruct.IdLayerMethod AS L17, t1.L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, t1.L4 AS L4, t1.L5 AS L5, t1.L6 AS L6, t1.L7 AS L7, t1.L8, t1.L9, t1.L10, t1.L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16 FROM (SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8, DataMIR.IdLayerStruct AS L9, DataMIR.Ind AS L10, DataMIR.MC AS L11, DataMIR.ME AS L12, UnitsDimension.IdUnitDimension AS L13, FrecMeasure.IdFrecMeasure AS L14, ChainResults.IdChainResult AS L15, DataMIR.Unit AS L16 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind = 0 UNION SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8, DataMIR.IdLayerStruct AS L9, DataMIR.Ind AS L10, DataMIR.MC AS L11, DataMIR.ME AS L12, UnitsDimension.IdUnitDimension AS L13, FrecMeasure.IdFrecMeasure AS L14, ChainResults.IdChainResult AS L15, DataMIR.Unit AS L16 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind != 0) AS t1 RIGHT JOIN LayersStruct ON t1.L8 = LayersStruct.IdLayerStruct WHERE LayersStruct.OrderMML != 0 ORDER BY L2;");
		///////////////////////////////
		
		//$res = $this->bConnection->query("SELECT t1.L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, IFNULL(t1.L4,'Sin terminar') AS L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L10 FROM (SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9, DataMIR.Ind AS L10 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind = 0 UNION SELECT DataMIR.IdRegister AS L1, LayersStruct.OrderMML AS L2, LayersStruct.Code AS L3, DataMIR.RN AS L4, DataMIR.NI AS L5, DataMIR.FV AS L6, DataMIR.SU AS L7, LayersStruct.IdLayerStruct AS L8,LayersStruct.IdLayerStruct AS L9, DataMIR.Ind AS L10  FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND DataMIR.Ind != 0) AS t1 RIGHT JOIN LayersStruct ON t1.L9 = LayersStruct.IdLayerStruct WHERE LayersStruct.OrderMML != 0 ORDER BY L2;");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return "SELECT * FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p;
	}
	
	public function getRegUpdate($p)
	{
		
		$this->OpenDB();
		 $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT Registers.IdRegister AS L1, Profiles.IdProfile AS L2, Profiles.Profile AS L2a, LocTowns.IdLocTown AS L3, LocTowns.Name AS L4, LocCounties.IdLocCounty AS L5, LocCounties.Name AS L6, LocStates.IdLocState AS eL7, LocStates.Name AS L8, AcademicLevels.IdAcademicLevel AS L9, AcademicLevels.Level AS L10, Registers.Grade AS L11, Registers.SchoolName AS L12, Registers.Mn AS L13, Registers.Fn AS L14, Registers.Student AS L15, Registers.ProjName AS L16, Problems.IdProblem AS L17, Problems.Problem AS L18, Registers.HeadProblem AS L19, Registers.ProjGoal AS L20, Registers.IdManager AS L21, SubProblems.IdSubProblem AS L22, SubProblems.SubProblem AS L23 FROM Registers INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN LocTowns ON Registers.IdTown = LocTowns.IdLocTown INNER JOIN LocCounties ON LocTowns.IdLocCounty = LocCounties.IdLocCounty INNER JOIN LocStates ON LocCounties.IdLocState = LocStates.IdLocState INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON Problems.IdProblem = SubProblems.IdProblem WHERE Registers.Status = 1 AND Profiles.Status = 1 AND LocTowns.Status = 1 AND LocCounties.Status = 1 AND LocStates.Status = 1 AND AcademicLevels.Status = 1 AND SubProblems.Status = 1 AND Problems.Status = 1 AND Registers.IdRegister = ".$p);
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function setRegUpdate($json)
	{
		$a = json_decode($json, true);
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("UPDATE Registers SET IdTown=".$a['Town']." ,SchoolName='".$a['School']."',ProjName='".$a['ProjName']."',HeadProblem='".$a['Problem']."',ProjGoal='".$a['Goal']."',Mn=".$a['Male'].",Fn=".$a['Female'].",Tn=".$a['Total'].",ProjectType=".$a['Profile'].", IdSubProblem=".$a['IdProblem'].",Grade='".$a['Grade']."',IdAcademicLevel=".$a['Level'].", Student=".$a['Student'].", IdManager=".$a['Manager'].", UpdateUser = '".$_SESSION['nme']."' WHERE IdRegister = ".$a['No'].";");
		$this->CloseDB();
		return $res;
	}
	
	//
	public function getManagers()
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS Code, 'Selecciona un asesor' AS Name UNION SELECT Users.IdUser AS Code, Users.User AS Name  FROM Users WHERE Users.Status = 1 AND Users.IdProfile = 6");
		
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	/*public function GetComboAO()
	{
		$this->OpenDB();
		//$res = $this->bConnection->query("");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}*/
	
	public function getDataMIRByComp($p, $s)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT DataMIR.IdDataMIR AS L0, DataMIR.RN AS L1, DataMIR.NI AS L2, DataMIR.MC AS L3, DataMIR.Unit AS L4, DataMIR.ME AS L5, FrecMeasure.IdFrecMeasure AS L6, UnitsDimension.IdUnitDimension AS L7, ChainResults.IdChainResult AS L8, DataMIR.FV AS L9, DataMIR.SU AS L10, DataMIR.IdLayerStruct AS L11, DataMIR.Ind AS L12 FROM LayersStruct INNER JOIN DataMIR ON LayersStruct.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE LayersStruct.Status = 1 AND DataMIR.Status = 1 AND LayersStruct.Code = '".$s."' AND DataMIR.IdRegister = ".$p." AND FrecMeasure.IdFrecMeasure AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 ORDER BY L12 ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	
	public function getReportComponent($p, $s)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		
		$res = $this->bConnection->query("SELECT DISTINCT DataUpload.Hash AS L1, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L2/*, DataUpload.DateReg AS L3*/ FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 /*AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0)*/ AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' ORDER BY L2 DESC");
		//$res = $this->bConnection->query("SELECT DISTINCT DataUpload.Hash AS L1, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L2 FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 /*AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0)*/ AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' ORDER BY L32 DESC");
		//$res = $this->bConnection->query("SELECT DISTINCT DataUpload.Hash AS L1, DataUpload.DateReg AS L2,  (SELECT DISTINCT COUNT(DataUpload.Hash) AS L1 FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS L3  FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 /*AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0)*/ AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' ORDER BY L2 DESC");
		$rows= array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);

			}
		
		$de = array();
		foreach($rows as $rd)
		{
			
			//$res = $this->bConnection->query("SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/ FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataUpload.Hash = '".$rd['L1']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataUpload.Hash = '".$rd['L1']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 300 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser /*WHERE t4.Status = 1 AND t5.Status = 1*/;");
			$res = $this->bConnection->query("SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/ FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, 'N/A' /*DataUpload.Rev2*/ AS L15, 'N/A' /*DataUpload.RevDate2*/ AS L16, DataUpload.RevDE1 AS L17, 'N/A' /*DataUpload.RevDE2*/ AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 /*AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0)*/ AND DataUpload.Status = 1 AND LayersStruct.Status = 1 /*AND DataUpload.Pathext IN ('jpg','png')*/ AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataUpload.Hash = '".$rd['L1']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, 'N/A' /*DataUpload.Rev2*/ AS L15, 'N/A' /*DataUpload.RevDate2*/ AS L16, DataUpload.RevDE1 AS L17, 'N/A' /*DataUpload.RevDE2*/ AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 /*AND (ISNULL(DataUpload.Rev1) = 1 OR ISNULL(DataUpload.Rev2) = 1 OR DataUpload.Rev1 = 0 OR DataUpload.Rev2 = 0)*/ AND DataUpload.Status = 1 AND LayersStruct.Status = 1 /*AND DataUpload.Pathext IN ('jpg','png')*/ AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataUpload.Hash = '".$rd['L1']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 300 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser /*WHERE t4.Status = 1 AND t5.Status = 1*/;");
			$rows = array();
			while($r = mysqli_fetch_assoc($res)) {
				$rows[] = array_map(null, $r);
			}
			if(count($rows) > 0)
			{
				$de[] = $rows;
			}
			//$sd = $sd.'@'.$rd['L1'];
		}

		$this->CloseDB();
		return json_encode($de);//$sd;//$p.", php, ".$s.", ".$rowsDate[1]['L1'].', '.$rowsDate[0]['L1'];// json_encode($rows);
	}
	
	public function getReportComponentByProject($p)
	{
		$rt = array();
		
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');

		$res = $this->bConnection->query("SELECT DISTINCT /*DataUpload.Hash AS L1,*/ LayersStruct.Code AS L2/*, DataUpload.Rev2, DataUpload.Rev1*/ FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 /*AND ISNULL(DataUpload.Rev2) = 0*/ AND DataUpload.Rev1 != 0 /*AND DataUpload.Rev2 != 0*/) AND DataUpload.IdRegister = ".$p.";");
		$rows= array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);

			}
		
		$de = array();
		foreach($rows as $rd)
		{

			//$res = $this->bConnection->query("SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/ FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 1000 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser /*WHERE t4.Status = 1 AND t5.Status = 1*/;");
			
			//$res = $this->bConnection->query("SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/ FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."' AND DataUpload.Hash = '".$rd['L1']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."' AND DataUpload.Hash = '".$rd['L1']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 1000 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser /*WHERE t4.Status = 1 AND t5.Status = 1*/;");

			
			/////////////////////////////77
			
			$res = $this->bConnection->query("SELECT * FROM (SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/ FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, 'N/A' /*DataUpload.Rev2*/ AS L15, 'N/A' /*DataUpload.RevDate2*/ AS L16, DataUpload.RevDE1 AS L17, 'N/A' /*DataUpload.RevDE2*/ AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 /*AND ISNULL(DataUpload.Rev2) = 0*/ AND DataUpload.Rev1 != 0 /*AND DataUpload.Rev2 != 0*/) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, 'N/A' /*DataUpload.Rev2*/ AS L15, 'N/A' /*DataUpload.RevDate2*/ AS L16, DataUpload.RevDE1 AS L17, 'N/A' /*DataUpload.RevDE2*/ AS L18  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 /*AND ISNULL(DataUpload.Rev2) = 0*/ AND DataUpload.Rev1 != 0 /*AND DataUpload.Rev2 != 0*/) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 1000 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser) AS tb  INNER JOIN (SELECT * FROM (SELECT DISTINCT DataMIR.RN AS W1, DataMIR.NI AS W2, DataMIR.MC AS W3, DataMIR.ME AS W4, DataMIR.FV AS W5, DataMIR.SU AS W6, LayersStruct.Code AS W7, DataMIR.Unit AS W8, FrecMeasure.Frec AS W9, UnitsDimension.Dimension AS W10, ChainResults.ChainResult AS W11 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Ind = 0 AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 UNION SELECT DISTINCT DataMIR.RN AS W1, DataMIR.NI AS W2, DataMIR.MC AS W3, DataMIR.ME AS W4, DataMIR.FV AS W5, DataMIR.SU AS W6, LayersStruct.Code AS W7, DataMIR.Unit AS W8, FrecMeasure.Frec AS W9, UnitsDimension.Dimension AS W10, ChainResults.ChainResult AS W11 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Ind != 0 AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1) AS t1) AS ta ON  tb.L9 = ta.W7");
			//$res = $this->bConnection->query("SELECT * FROM (SELECT DISTINCT CONCAT(t6.User,' - ', STR_TO_DATE(t3.L6,'%Y-%m-%d')) AS L0, t3.L1, t3.L2, t3.L3, t3.L4, t3.L5, t3.L6, t3.L7, t3.L8, t3.L9, t3.L10, t3.L11 AS L11, t3.L12, CONCAT(t4.User, ' - ' , STR_TO_DATE(t3.L14,'%Y-%m-%d')) AS L28 , t3.L17 AS L29, CONCAT(t5.User, ' - ' , STR_TO_DATE(t3.L16,'%Y-%m-%d')) AS L31 , t3.L18 AS L30/*, t6.User AS 32*/, t3.L19 AS L35 FROM (SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8, t1.L9, t1.L10, t1.L11 AS L11, t1.L12, t1.L13, t1.L14, t1.L15, t1.L16, t1.L17, t1.L18, t1.L19 FROM (SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18, DataUpload.b64 AS L19  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."' UNION SELECT DataMIR.IdRegister AS L1, DataMIR.Me AS L2, DataUpload.PR AS L3, DataUpload.Name AS L4, LayersStruct.Struct AS L5, DATE_FORMAT(DataUpload.DateReg, '%Y-%m-%d') AS L6, DataUpload.DE AS L7, DataUpload.Data AS L8, LayersStruct.Code AS L9, DataUpload.DateReg AS L10, DataUpload.Hash AS L11, DataUpload.Pathext AS L12, DataUpload.Rev1 AS L13, DataUpload.RevDate1 AS L14, DataUpload.Rev2 AS L15, DataUpload.RevDate2 AS L16, DataUpload.RevDE1 AS L17, DataUpload.RevDE2 AS L18, DataUpload.b64 AS L19  FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND (ISNULL(DataUpload.Rev1) = 0 AND ISNULL(DataUpload.Rev2) = 0 AND DataUpload.Rev1 != 0 AND DataUpload.Rev2 != 0) AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.Pathext IN ('jpg','png') AND DataMIR.IdRegister = ".$p." AND LayersStruct.Code = '".$rd['L2']."') AS t1 WHERE t1.L6 BETWEEN DATE_SUB(NOW(),INTERVAL 1000 DAY) AND NOW() ORDER BY t1.L5, t1.L6 ASC) AS t3 INNER JOIN Registers ON t3.L1 = Registers.IdRegister LEFT JOIN Users AS t4 ON t3.L13 = t4.IdUser LEFT JOIN Users AS t5 ON t3.L15 = t5.IdUser LEFT JOIN Users AS t6 ON Registers.IdUser = t6.IdUser) AS tb  INNER JOIN (SELECT * FROM (SELECT DISTINCT DataMIR.RN AS W1, DataMIR.NI AS W2, DataMIR.MC AS W3, DataMIR.ME AS W4, DataMIR.FV AS W5, DataMIR.SU AS W6, LayersStruct.Code AS W7, DataMIR.Unit AS W8, FrecMeasure.Frec AS W9, UnitsDimension.Dimension AS W10, ChainResults.ChainResult AS W11 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Ind = 0 AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 UNION SELECT DISTINCT DataMIR.RN AS W1, DataMIR.NI AS W2, DataMIR.MC AS W3, DataMIR.ME AS W4, DataMIR.FV AS W5, DataMIR.SU AS W6, LayersStruct.Code AS W7, DataMIR.Unit AS W8, FrecMeasure.Frec AS W9, UnitsDimension.Dimension AS W10, ChainResults.ChainResult AS W11 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Ind != 0 AND DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1) AS t1) AS ta ON  tb.L9 = ta.W7");
			/////////////////////////////////
			
			$rows = array();
			while($r = mysqli_fetch_assoc($res)) {
				$rows[] = array_map(null, $r);
			}
			if(count($rows) > 0)
			{
				$de[] = $rows;
			}

		}
		$rt[0] = $de;
		$this->CloseDB();
		return json_encode($rt);
	}
	
	public function getTableSM()
	{
		
		$da = 5;
	
	
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		$res = $this->bConnection->query("SELECT Users.IdProfile AS L1 FROM Users WHERE Users.User = '".$_SESSION['nme']."' AND Users.Status = 1");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];

		if($strR == 2)
		{
			$res = $this->bConnection->query("SELECT COUNT(t1.L1) AS t0, t1.L1, t1.L2, SUM(t1.L3) AS L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8 FROM (SELECT DataUpload.IdRegister AS L1, DataMIR.ME AS L2, DataUpload.PR AS L3, STR_TO_DATE(DataUpload.DateReg, '%Y-%m-%d') AS L4, DataMIR.IdLayerStruct AS L5  , LayersStruct.Struct AS L6, Registers.ProjName AS L7, Users.User AS L8 FROM DataUpload  INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN Registers ON DataMIR.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND Users.Status = 1 AND Registers.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.DateReg BETWEEN DATE_SUB(NOW(),INTERVAL ".$da." DAY) AND NOW()) AS t1 GROUP BY L1, L4, L5 ORDER BY t1.L4, t1.L5;");
		}
		else
		{
		
		
			$res = $this->bConnection->query("SELECT COUNT(t1.L1) AS t0, t1.L1, t1.L2, SUM(t1.L3) AS L3, t1.L4, t1.L5, t1.L6, t1.L7, t1.L8 FROM (SELECT DataUpload.IdRegister AS L1, DataMIR.ME AS L2, DataUpload.PR AS L3, STR_TO_DATE(DataUpload.DateReg, '%Y-%m-%d') AS L4, DataMIR.IdLayerStruct AS L5  , LayersStruct.Struct AS L6, Registers.ProjName AS L7, Users.User AS L8 FROM DataUpload  INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN Registers ON DataMIR.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE DataUpload.Status = 1 AND Users.User = '".$_SESSION['nme']."'  AND DataMIR.Status = 1 AND Users.Status = 1 AND Registers.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.DateReg BETWEEN DATE_SUB(NOW(),INTERVAL ".$da." DAY) AND NOW()) AS t1 GROUP BY L1, L4, L5 ORDER BY t1.L4, t1.L5;");
		
		}
		
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return "desde php";
	}
	
	
	
	
	
	//
	public function getInfoLocation($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT LocStates.Name AS L1, LocCounties.Name AS L2, LocTowns.Name AS L3, LocStates.WikiURL AS L4 FROM LocStates INNER JOIN LocCounties ON LocStates.IdLocState = LocCounties.IdLocState INNER JOIN LocTowns ON LocCounties.IdLocCounty = LocTowns.IdLocCounty INNER JOIN Registers ON LocTowns.IdLocTown = Registers.IdTown WHERE LocStates.Status = 1 AND LocCounties.Status = 1 AND LocTowns.Status =1 AND Registers.IdRegister =".$p);
        $rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
    
    }
	
	
	
	public function getInfoSM($p, $s)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT DISTINCT Registers.ProjName AS L1, Registers.SchoolName AS L2, DataMIR.RN AS L3, DataMIR.NI AS L4, DataMIR.MC AS L5, DataMIR.ME AS L6, FrecMeasure.Frec AS L7, UnitsDimension.Dimension AS L8, ChainResults.ChainResult AS L9, DataMIR.FV AS L10, DataMIR.SU AS L11, Registers.HeadProblem AS L12, Registers.ProjGoal AS L13, Registers.Tn AS L14, Problems.Problem AS L15, Registers.Mn AS L16, Registers.Fn AS L17, Registers.Student AS L18, SubProblems.SubProblem AS L19, CONCAT(AcademicLevels.Level, ', ', Registers.Grade) AS L20, Users.User AS L21, DataMIR.FV AS  L22, DataMIR.SU AS L23, DataMIR.Unit AS L24, DataMIR.MC AS L25 FROM DataUpload INNER JOIN Registers ON DataUpload.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser INNER JOIN SubProblems ON SubProblems.IdSubProblem = Registers.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE DataUpload.Status = 1 AND Registers.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataMIR.Status = 1 AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 AND Problems.Status = 1 AND SubProblems.Status = 1 AND AcademicLevels.Status = 1 UNION SELECT DISTINCT Registers.ProjName AS L1, Registers.SchoolName AS L2, DataMIR.RN AS L3, DataMIR.NI AS L4, DataMIR.MC AS L5, DataMIR.ME AS L6, FrecMeasure.Frec AS L7, UnitsDimension.Dimension AS L8, ChainResults.ChainResult AS L9, DataMIR.FV AS L10, DataMIR.SU AS L11, Registers.HeadProblem AS L12, Registers.ProjGoal AS L13, Registers.Tn AS L14, Problems.Problem AS L15, Registers.Mn AS L16, Registers.Fn AS L17, Registers.Student AS L18, SubProblems.SubProblem AS L19, CONCAT(AcademicLevels.Level, ' en los grados ', Registers.Grade) AS L20, Users.User AS L21, DataMIR.FV AS  L22, DataMIR.SU AS L23, DataMIR.Unit AS L24, DataMIR.MC AS L25 FROM DataUpload INNER JOIN Registers ON DataUpload.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser INNER JOIN SubProblems ON SubProblems.IdSubProblem = Registers.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel WHERE DataUpload.Status = 1 AND Registers.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataMIR.Status = 1 AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 AND Problems.Status = 1 AND SubProblems.Status = 1 AND AcademicLevels.Status = 1");
		//$res = $this->bConnection->query("SELECT DISTINCT Registers.ProjName AS L1, Registers.SchoolName AS L2, DataMIR.RN AS L3, DataMIR.NI AS L4, DataMIR.MC AS L5, DataMIR.ME AS L6, FrecMeasure.Frec AS L7, UnitsDimension.Dimension AS L8, ChainResults.ChainResult AS L9, DataMIR.FV AS L10, DataMIR.SU AS L11, Registers.HeadProblem AS L12, Registers.ProjGoal AS L13, Registers.Tn AS L14, Problems.Problem AS L15, Registers.Mn AS L16, Registers.Fn AS L17, Registers.Student AS L18, SubProblems.SubProblem AS L19 FROM DataUpload INNER JOIN Registers ON DataUpload.IdRegister = Registers.IdRegister INNER JOIN SubProblems ON SubProblems.IdSubProblem = Registers.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult  WHERE DataUpload.Status = 1 AND Registers.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataMIR.Status = 1 AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 AND Problems.Status = 1 AND SubProblems.Status = 1 UNION SELECT DISTINCT Registers.ProjName AS L1, Registers.SchoolName AS L2, DataMIR.RN AS L3, DataMIR.NI AS L4, DataMIR.MC AS L5, DataMIR.ME AS L6, FrecMeasure.Frec AS L7, UnitsDimension.Dimension AS L8, ChainResults.ChainResult AS L9, DataMIR.FV AS L10, DataMIR.SU AS L11, Registers.HeadProblem AS L12, Registers.ProjGoal AS L13, Registers.Tn AS L14, Problems.Problem AS L15, Registers.Mn AS L16, Registers.Fn AS L17, Registers.Student AS L18, SubProblems.SubProblem AS L19 FROM DataUpload INNER JOIN Registers ON DataUpload.IdRegister = Registers.IdRegister INNER JOIN SubProblems ON SubProblems.IdSubProblem = Registers.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN FrecMeasure ON DataMIR.IdFrecMeasure = FrecMeasure.IdFrecMeasure INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult  WHERE DataUpload.Status = 1 AND Registers.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' AND DataMIR.Status = 1 AND FrecMeasure.Status = 1 AND UnitsDimension.Status = 1 AND ChainResults.Status = 1 AND Problems.Status = 1 AND SubProblems.Status = 1");
        $rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
        //return $strR;
    }
	
	public function getInfoByProject($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');

        $res = $this->bConnection->query("SELECT t1.User AS L1, t2.User AS L2, Registers.SchoolName AS L3, Registers.ProjName AS L4, Registers.HeadProblem AS L5, Registers.ProjGoal AS L6, CONCAT(AcademicLevels.Level,' en los grados ',Registers.Grade) AS L7, Registers.Mn AS L8, Registers.Fn AS L9, Registers.Tn AS L10, Profiles.Profile AS L11, Registers.Student AS L12, SubProblems.SubProblem AS L13, Problems.Problem AS L14 FROM Registers INNER JOIN Users AS t1 ON Registers.IdUser = t1.IdUser INNER JOIN Users AS t2 ON Registers.IdManager = t2.IdUser INNER JOIN LocTowns ON Registers.IdTown = LocTowns.IdLocTown INNER JOIN LocCounties ON LocTowns.IdLocCounty = LocCounties.IdLocCounty INNER JOIN LocStates ON LocCounties.IdLocState = LocStates.IdLocState INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN AcademicLevels ON Registers.IdAcademicLevel = AcademicLevels.IdAcademicLevel INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Registers.Status = 1 AND t1.Status = 1 AND t2.Status = 1 AND LocTowns.Status = 1 AND LocCounties.Status = 1 AND LocStates.Status = 1 AND Registers.IdRegister = ".$p." AND Profiles.Status = 1 AND SubProblems.Status = 1 AND Problems.Status = 1 AND AcademicLevels.Status = 1;");
		//$res = $this->bConnection->query("SELECT t1.User AS L1, t2.User AS L2, Registers.SchoolName AS L3, Registers.ProjName AS L4, Registers.HeadProblem AS L5, Registers.ProjGoal AS L6, Registers.Grade AS L7, Registers.Mn AS L8, Registers.Fn AS L9, Registers.Tn AS L10, Profiles.Profile AS L11, Registers.Student AS L12, SubProblems.SubProblem AS L13, Problems.Problem AS L14 FROM Registers INNER JOIN Users AS t1 ON Registers.IdUser = t1.IdUser INNER JOIN Users AS t2 ON Registers.IdManager = t2.IdUser INNER JOIN LocTowns ON Registers.IdTown = LocTowns.IdLocTown INNER JOIN LocCounties ON LocTowns.IdLocCounty = LocCounties.IdLocCounty INNER JOIN LocStates ON LocCounties.IdLocState = LocStates.IdLocState INNER JOIN Profiles ON Registers.ProjectType = Profiles.IdProfile INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Registers.Status = 1 AND t1.Status = 1 AND t2.Status = 1 AND LocTowns.Status = 1 AND LocCounties.Status = 1 AND LocStates.Status = 1 AND Registers.IdRegister = ".$p." AND Profiles.Status = 1 AND SubProblems.Status = 1 AND Problems.Status = 1;");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
        //return $strR;
    }
	
	public function getChartPR($p, $s)
    {
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		////////////////
		//$res = $this->bConnection->query("SET @iMin = (SELECT t4.L2 FROM (SELECT L2, STR_TO_DATE(MIN(t1.L7),'%Y-%m-%d') AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6, DataUpload.DateReg AS L7 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6, DataUpload.DateReg AS L7 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5 LIMIT 1) AS t4); SELECT t1.L1, SUM(t1.L2) AS L2, t1.L3, t1.L4, t1.L5, @iMin AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5");
		
		//$res = $this->bConnection->query("SELECT L2, STR_TO_DATE(MIN(t1.L1),'%Y-%m-%d') AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6/*, DataUpload.DateReg AS L7*/ FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6/*, DataUpload.DateReg AS L7*/ FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5 LIMIT 1;");
		$res = $this->bConnection->query("SELECT t5.L2 AS L2 FROM (SELECT MIN(t4.L1)  AS L1, t4.L2 FROM (SELECT t1.L1, SUM(t1.L2) AS L2, t1.L3, t1.L4, t1.L5 AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5) AS t4 LIMIT 1) AS t5");
		$row = $res->fetch_assoc();
		$iMin = $row['L2'];
		$res = $this->bConnection->query("SELECT /*t1.L1, SUM(t1.L2) AS L2, CONCAT(t1.L1, ' - ', t1.L6)*/ t1.L1 AS L1, t1.L2, t1.L3, t1.L4, t1.L5, ".$iMin." AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, DataMIR.NI/*LayersStruct.Code*/ AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, DataMIR.NI/*LayersStruct.Code*/ AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 /*GROUP BY  L1, L3, L4, L5*/;");
		/////////
		
		
		//$res = $this->bConnection->query("SELECT t1.L2, SUM(t1.L3) AS L3, t1.L4, t1.L6, t1.L7 FROM (SELECT COUNT(DataUpload.IdDataUpload) AS L1, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L2, DataUpload.PR AS L3, LayersStruct.Code AS L4, DataUpload.Hash AS L5, DataMIR.ME AS L6, DataMIR.NI AS L7 FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.IdRegister = ".$p." AND DataMIR.Status =1 AND LayersStruct.Code = '".$s."' GROUP BY L2, L3,L4, L5 ) AS t1 GROUP BY L2,L4, L6;");
		//$res = $this->bConnection->query("SELECT t1.L2, SUM(t1.L3) AS L3, t1.L4 FROM (SELECT COUNT(DataUpload.IdDataUpload) AS L1, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L2, DataUpload.PR AS L3, LayersStruct.Code AS L4, DataUpload.Hash AS L5  FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' GROUP BY L2, L3,L4, L5 ) AS t1 GROUP BY L2,L4;");
		//$res = $this->bConnection->query("SELECT t1.L1, t1.L2, t1.L3, t1.L4, t1.L5 FROM (SELECT COUNT(DataUpload.IdDataUpload) AS L1, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L2, DataUpload.PR AS L3, LayersStruct.Code AS L4, DataUpload.Hash AS L5  FROM DataUpload INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND LayersStruct.Status = 1 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."' GROUP BY L2,L3,L4, L5 ) AS t1 ");
		$pr = array();
		$pr['name']  ='Progreso parcial';
		$pr['type'] = 'waterfall';
		
		$fm = array();
		$fm['name'] = 'Frecuencia';
		
		$me = array();
		$me['name'] = 'Meta del indicador';
		$me['type'] = 'spline';
		$me['color'] = '#00ff00';

		
		$min = array();
		$min['name'] = 'Deficiente';
		$min['type'] = 'spline';
		$min['color'] = '#ff0000';
		
		$ti = array();
		$ti['title'] = 'Titulo';
		
		
		
		//////////////////////////
		$pie = array();
		
		$res2 = $this->bConnection->query("SELECT SUM(t3.L2) AS L2, t3.L4, SUM(t3.L2) * 100 / t3.L4 AS L8, 100 - SUM(t3.L2) * 100 / t3.L4 AS L9 FROM (SELECT t1.L1, SUM(t1.L2) AS L2, t1.L3, t1.L4, t1.L5, 6 AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5) AS t3  GROUP BY L4;");
		$row2 = $res2->fetch_assoc();
		$pie['data'][] = round($row2['L8']);
		
		
		/////////////////////////////////////////////////////////////////////7////////////////////////////////////////////////////////////////////7
		/*$pie = array();
		$pie['name'] = 'Total';
		$pie['type'] = 'pie';
		$pie['title'] = '% Total de progreso por indicador';
		
		
		
		/////////////////////////////////////////////////////////7
		$red = array(0.1, '#ff0000');
		$yellow = array(0.6, '#ffff00');
		$green = array(0.9, '#00ff00');
		$arrC = array($red, $yellow, $green);
		$pie['yAxis'] = array('stops' => $arrC);
		////////////////////////////////////////////////////////
		
		
		
		$pie['innerSize'] ='50%';
		$pie['showInLegend'] = 'false';
		$pie['dataLabels'] = array('enabled' => false);
		
		$res2 = $this->bConnection->query("SELECT SUM(t3.L2) AS L2, t3.L4, SUM(t3.L2) * 100 / t3.L4 AS L8, 100 - SUM(t3.L2) * 100 / t3.L4 AS L9 FROM (SELECT t1.L1, SUM(t1.L2) AS L2, t1.L3, t1.L4, t1.L5, 6 AS L7 FROM (SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND DataUpload.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 AND LayersStruct.Status = 1 AND LayersStruct.Code = '".$s."' UNION SELECT DISTINCT STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L1, DataUpload.PR AS L2, LayersStruct.Code AS L3, DataMIR.ME AS L4, DataMIR.NI AS L5, DataUpload.Hash AS L6 FROM DataUpload INNER JOIN DataMIR ON DataUpload.IdRegister = DataMIR.IdRegister AND DataUpload.IdLayerStruct = DataMIR.Ind INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataUpload.Status = 1 AND DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.IdRegister = ".$p." AND LayersStruct.Code = '".$s."') AS t1 GROUP BY  L1, L3, L4, L5) AS t3  GROUP BY L4;");
		$row2 = $res2->fetch_assoc();
		$a = $row2['L8'];
		$b = $row2['L9'];
		//$pie['data'][]= array('y' => $a, 'name' => '% - Progreso acumulado');
		
		$color = '#00ffff';
			if( $a < 33)
			{
				$color1 = '#FE2E2E'; //red
				$color2 = '#F5A9A9';
				
			}
			else if($a >= 33 && $a < 66)
			{
				$color1 = '#F7FE2E'; //yellow
				$color2 = '#F2F5A9';
			}
			else if($a >= 66)
			{
				$color1 = '#2EFE2E'; //green
				$color2 = '#A9F5A9';
			}
		
		$pie['data'][] = array('y' => $a,'color' => $color1, 'name' => '% - Progreso acumulado');
		$pie['data'][] = array('y' => $b,'color' => $color2, 'name' => '% - Restante del indicador');
		
		
		*/
		/*$pie['data'][] = array('y' => $a,'color' => '#58ACFA', 'name' => '% - Progreso acumulado');
		$pie['data'][] = array('y' => $b,'color' => '#CEE3F6', 'name' => '% - Restante del indicador');*/
		/////////////////////////////////////////////////////////////////////7////////////////////////////////////////////////////////////////////7
		

		/////////////////////////////////////////////////////////////////////7
		$w= 0;
		while($r = mysqli_fetch_assoc($res))
		{
			
			$color = '#FF0040';
			//$color = '#FE2E64';
			/*if( $r['L2'] < $r['L7'])
			{
				$color = '#F78181'; //red
				$pr['color'] = $color;
				$name = 'Última entrega deficiente, ¡sigue trabajando!';
			}
			else if($r['L2'] >= $r['L7'] && $r['L2'] < $r['L4'])
			{
				$color = '#F4FA58'; //yellow
				$pr['color'] = $color;
				$name = 'Última entrega en orden, ¡muy bien, sigue así!';
			}
			else if($r['L2'] >= $r['L4'])
			{
				$color = '#81F781'; //green
				$pr['color'] = $color;
				$name = 'Última entrega sobresaliente, ¡superando expectativas!';
			}*/
			$w = $w + $r['L2'];
			if($w < ($r['L4']+0))
			{
				$color = '#F4FA58'; //yellow
				$pr['color'] = $color;
				$name = 'Última entrega en orden, ¡muy bien, sigue así!';
			}
			else 
			{
				$color = '#04B404'; //green
				$pr['color'] = $color;
				$name = 'Última entrega sobresaliente, ¡superando expectativas!';
			}
			
			//$name = 'Última entrega registrada';
			$pr['data'][] = array('y' => $r['L2'],'color' => $color, 'name' => $name);
			$fm['data'][] = $r['L1'];
			$me['data'][] = $r['L4'];
			$min['data'][] = $r['L7'];
			$ti['text'] = $r['L5'];
		}
		
		
		$this->CloseDB();
		$result = array();
		array_push($result,$pr);
		array_push($result,$fm);
		array_push($result,$me);
		array_push($result,$ti);
		array_push($result,$min);
		array_push($result,$pie);
		return json_encode($result, JSON_NUMERIC_CHECK);
    }
	
	public function getChartByElement($p)
    {
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		
		
		$res3 = $this->bConnection->query("SELECT SUM(t3.L0) AS L0, t3.L1, t3.L2 FROM (SELECT DISTINCT IFNULL(t1.L2,0) AS L0, t1.L5, t2.L1, t2.L2, t1.L9 FROM (SELECT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataUpload.Hash AS L5, LEFT(LayersStruct.Code,2) AS L8, LayersStruct.OrderMML AS L9 FROM DataMIR INNER JOIN DataUpload ON DataMIR.Ind = DataUpload.IdLayerStruct AND DataMIR.IdRegister = DataUpload.IdRegister INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND LENGTH(DataUpload.Hash) != 0) AS t1 INNER JOIN (SELECT SUM(DataMIR.ME) AS L1, LEFT(LayersStruct.Code,2) AS L2 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND LayersStruct.IdLayerMethod = 8 GROUP BY L2) AS t2 ON t1.L8 = t2.L2) AS t3 GROUP BY L1,L2 ORDER BY L9 ASC;");
		$pr2 = array();
		$pr2['name'] = 'Cumplimiento de los componentes';
		$pr2['type'] = 'column';
		$pr2['color'] = '#FF4000';
		$pr2['tooltip'] = array('valueSuffix' => ' unidades de avance');
		
		$co2 = array();
		$co2['name'] = 'Componentes';
		
		$me2 = array();
		$me2['name'] = 'Meta';
		$me2['type'] = 'spline';
		$me2['color'] = '#00ff00';
		$me2['tooltip'] = array('valueSuffix' => ' unidades');
		
		while($r = mysqli_fetch_assoc($res3))
		{
			$pr2['data'][] = $r['L0'];
			$co2['data'][] = $r['L2'];
			$me2['data'][] = $r['L1'];
		
		}
		
		$res4 = $this->bConnection->query("SELECT SUM(t1.L2) AS L2, t1.L1, t1.L8, t1.L9 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2,  DataUpload.Hash AS L5, LayersStruct.Code AS L8, LayersStruct.OrderMML AS L9 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdLayerStruct = DataUpload.IdLayerStruct AND DataMIR.IdRegister = DataUpload.IdRegister INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataUpload.Status = 1 AND LayersStruct.Status = 1 AND LENGTH(DataUpload.Hash) != 0 ) AS t1 GROUP BY L1,L8 ORDER BY L9 ASC;");
		$pr3 = array();
		$pr3['name'] = 'Calidad de los componentes';
		$pr3['type'] = 'column';
		$pr3['color'] = '#8904B1';
		$pr3['tooltip'] = array('valueSuffix' => ' unidades de avance');
		
		$co3 = array();
		$co3['name'] = 'Componente';
		
		$me3 = array();
		$me3['name'] = 'Meta';
		$me3['type'] = 'spline';
		$me3['color'] = '#00ff00';
		$me3['tooltip'] = array('valueSuffix' => ' unidades');
		
		while($r = mysqli_fetch_assoc($res4))
		{
			$pr3['data'][] = $r['L2'];
			$co3['data'][] = $r['L8'];
			$me3['data'][] = $r['L1'];
		
		}
		
		$res = $this->bConnection->query("SELECT t1.L0, t1.L1, SUM(t1.L2) AS L2, t1.L6, t1.L7 AS L7 FROM (/*SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, LayersStruct.Code AS L6, DataMIR.Unit AS L7 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1 AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 UNION*/ SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, /*DataMIR.NI*/ LayersStruct.Code AS L6, DataMIR.Unit AS L7 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1  AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 ) AS t1 GROUP BY L0,L1,L6 ORDER BY L3");
		//$res = $this->bConnection->query("SELECT t1.L0, t1.L1, SUM(t1.L2) AS L2, t1.L6, t1.L7 AS L7 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, /*DataMIR.NI*/LayersStruct.Code AS L6, DataMIR.Unit AS L7 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1 AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 UNION SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, /*DataMIR.NI*/ LayersStruct.Code AS L6, DataMIR.Unit AS L7 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1  AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0 ) AS t1 GROUP BY L0,L1,L6 ORDER BY L3");
		$pr = array();
		$pr['name'] = 'Indicadores de actividad';
		$pr['type'] = 'column';
		
		

		$co = array();
		$co['name'] = 'Componente';
		
		$me = array();
		$me['name'] = 'Meta';
		$me['type'] = 'spline';
		$me['color'] = '#00ff00';
		
		$pie = array();
		//$res2 = $this->bConnection->query("SELECT t4.L0 AS L0, t4.L2 AS L3, Registers.ProjName AS L2, ((t4.L0 * 100)/ t4.L2) AS L1, 100 - ((t4.L0 * 100)/ t4.L2) AS L4 FROM (SELECT t2.L0 AS L0, t3.L0 AS L2, t3.L1 AS L1 FROM (SELECT SUM(t0.PR) AS L0, t0.IdRegister AS L1 FROM (SELECT DISTINCT DataUpload.PR, DataUpload.IdLayerStruct, DataUpload.IdRegister, DataUpload.Hash, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS Date  FROM DataUpload WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$p.") AS t0 GROUP BY L1) AS t2 RIGHT JOIN (SELECT SUM(t1.Me) AS L0, t1.IdRegister AS L1 FROM (SELECT DataMIR.ME, DataMIR.IdLayerStruct, DataMIR.Ind, DataMIR.IdRegister FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 GROUP BY L1) AS t3 ON t2.L1 = t3.L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE Registers.Status = 1 ORDER BY L2 DESC;");
		
		$res2 =$this->bConnection->query("SELECT SUM(tB) AS L1, SUM(tA) AS L4, tC AS L2 FROM (SELECT (L9*80)/L1 AS tB, 80 AS tA , L14 AS tC FROM (SELECT SUM(t5.L1) AS L1, SUM(t5.L5) AS L9, t5.L14 FROM (SELECT t4.L4 AS L1, t4.L5, Registers.ProjName AS L14 FROM (SELECT t3.L1, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, IF(L5 > L4,L4,L5) AS L5 FROM (SELECT t6.IdRegister AS L1, t6.Ind AS L2, IFNULL(t5.L5,0) AS L5, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 AND DataMIR.IdRegister IN (".$p.")) AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.Ind FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister IN (".$p.") AND DataMIR.Ind != 0) AS t6 ON t5.L2 = t6.Ind AND t5.L1 = t6.IdRegister) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND LayersStruct.IdLayerMethod IN (8)) AS t3 GROUP BY L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE  Registers.Status = 1) AS t5 GROUP BY L14) AS t6 UNION SELECT (L9*20)/L1 AS tB, 20 AS tA, L14  AS tC FROM (SELECT SUM(t5.L1) AS L1,SUM(t5.L9) AS L9, t5.L14 FROM (SELECT t4.L4 AS L1, t4.L5 AS L9, Registers.ProjName AS L14 FROM (SELECT t3.L1, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, IF(L5 > L4,L4,L5) AS L5 FROM (SELECT t6.IdRegister AS L1, IFNULL(t5.L5,0) AS L5, t6.Ind AS L2, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 AND DataMIR.IdRegister IN (".$p.")) AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN  (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.IdLayerStruct AS Ind FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister IN (".$p.") AND DataMIR.Ind = 0) AS t6 ON t5.L2 = t6.Ind AND t5.L1 = t6.IdRegister) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND LayersStruct.IdLayerMethod IN (3) ) AS t3 GROUP BY L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE Registers.Status = 1) AS t5 GROUP BY L14) AS t6) AS t7 GROUP BY L2 ORDER BY L1 DESC;");
		//$res2 = $this->bConnection->query("SELECT 100 - ((t4.L2 * 100) / t4.L1) AS Tot, ((t4.L2 * 100) / t4.L1) AS L2, Registers.ProjName AS L1 FROM (SELECT SUM(t3.L2) AS L2, SUM(t3.L1) AS L1, t3.L0 FROM (SELECT SUM(t2.L2) AS L2, SUM(t2.L1) AS L1, t2.L0, t2.L3 FROM (SELECT DISTINCT t1.L0, t1.L1, IFNULL(t0.L2,0) AS L2, t1.L3, t0.L4, t0.L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataUpload.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataUpload.Status = 1) AS t0 RIGHT JOIN (SELECT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataMIR.Ind AS L3 FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 ON t1.L3 = t0.L3 AND t0.L0 = t1.L0 ORDER BY L0,L3) AS t2 GROUP BY L0, L3) AS t3 GROUP BY L0) AS t4 INNER JOIN Registers ON t4.L0 = Registers.IdRegister WHERE Registers.Status = 1 ORDER BY L2 DESC");
		//$res2 = $this->bConnection->query("SELECT ((SUM(t1.L2) * 100)/SUM(t1.L1)) AS L1, 100 - ((SUM(t1.L2) * 100)/SUM(t1.L1)) AS L2 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, LayersStruct.Code AS L6 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1  AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0) AS t1;");
		$row2 = $res2->fetch_assoc();
		$pie['data'][] = round($row2['L1']);


		while($r = mysqli_fetch_assoc($res))
		{
			$pr['data'][] = $r['L2'];
			$co['data'][] = $r['L6'];
			$me['data'][] = $r['L1'];
		
		}
		$this->CloseDB();
		$result = array();
		array_push($result,$pr);
		array_push($result,$co);
		array_push($result,$me);
		array_push($result,$pie);
		array_push($result,$pr2);
		array_push($result,$co2);
		array_push($result,$me2);
		array_push($result,$pr3);
		array_push($result,$co3);
		array_push($result,$me3);
		return json_encode($result, JSON_NUMERIC_CHECK);
    }
	
	public function getHomeChart()
    {
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');


		$di = array();
		$di['name']  ='Dimensión';
		$di['type'] = 'column';
		$di['color'] = '#FF8000';
		$di['tooltip'] = array('valueSuffix' => ' indicadores');
		$diT = array();
		$diT['name'] = 'di';
		$diT['type'] = 'column';
		
		$ch = array();
		$ch['name']  ='Cuadro de honor';
		$ch['type'] = 'column';
		$ch['color'] = '#FF0040';
		//$ch['yAxis'] = array('tickInterval' => 0.01);
		$ch['tooltip'] = array('valueSuffix' => ' %');
		$chT = array();
		$chT['name'] = 'column';
		$chT['type'] = 'bar';
		
		//$chT['xAxis'] = array('type' => 'category', 'labels' => array('rotation' => -25, 'style' => array('fontSize' => '8px')));
		
		
		$cr = array();
		$cr['name'] = 'Cadena de resultados';
		$cr['type'] = 'column';
		$cr['color'] = '#67DC00';
		$cr['tooltip'] = array('valueSuffix' => ' indicadores');
		$crT = array();
		$crT['name'] = 'cr';
		$crT['type'] = 'column';
		
		$pr = array();
		$pr['name'] = 'Líneas de acción';
		$pr['type'] = 'column';
		$pr['tooltip'] = array('valueSuffix' => ' proyectos');
		$pr['color'] = '#E1FF57';
		$prT = array();
		$prT['name'] = 'cr';
		$prT['type'] = 'column';
		
		$ti = array();
		$ti['title'] = 'Titulo';
		
		$po = array();
		$po['name'] = 'Población';
		$po['type'] = 'pie';
		$po['innerSize'] ='50%';
		//$po['showInLegend'] = 'false';
		//$po['dataLabels'] = array('enabled' => false);
		//$po['tooltip'] = array('valueSuffix' => '');

		$res = $this->bConnection->query("SELECT Problems.Problem AS L1, COUNT(Registers.IdRegister) AS L2 FROM Registers INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Problems.Status = 1 AND SubProblems.Status = 1 AND Registers.Status = 1 GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$pr['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$prT['data'][] = $r['L1'];
		}
		
		$res = $this->bConnection->query("SELECT ChainResults.ChainResult AS L1, COUNT(DataMIR.IdDataMIR) AS L2 FROM DataMIR INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Status = 1 AND ChainResults.Status = 1 GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$cr['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$crT['data'][] = $r['L1'];
		}
		
		$res = $this->bConnection->query("SELECT UnitsDimension.Dimension AS L1, COUNT(DataMIR.IdDataMIR) AS L2 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension WHERE DataMIR.Status = 1 AND UnitsDimension.Status = 1 GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$di['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$diT['data'][] = $r['L1'];
		}
		
		//sin todos los proyectos
		//$res = $this->bConnection->query("SELECT t4.L1 AS L2, Registers.ProjName AS L1 FROM (SELECT (SUM(t2.L2) * 100 / SUM(t2.L1)) AS L1, t2.L0/*, 100 - (SUM(t2.L2) * 100 / SUM(t2.L1)) AS L2*/ FROM (SELECT t1.L0, t1.L1, SUM(t1.L2) AS L2, t1.L6 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, LayersStruct.Code AS L6 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1 /*AND DataMIR.IdRegister = 94*/ AND LENGTH(DataUpload.Hash) != 0 UNION SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataMIR.IdLayerStruct AS L3, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS L4, DataUpload.Hash AS L5, LayersStruct.Code AS L6 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1 /* AND DataMIR.IdRegister = 94*/ AND LENGTH(DataUpload.Hash) != 0 ) AS t1 GROUP BY L0,L1) AS t2 GROUP BY L0) AS t4 INNER JOIN Registers ON t4.L0 = Registers.IdRegister WHERE Registers.Status = 1 ORDER BY L2 DESC;");
		//todos los proyectos
		//$res = $this->bConnection->query("SELECT t4.L0 AS L0, t4.L2 AS L3, Registers.ProjName AS L1, ((t4.L0 * 100)/ t4.L2) AS L2, 100 - ((t4.L0 * 100)/ t4.L2) AS L4 FROM (SELECT t2.L0 AS L0, t3.L0 AS L2, t3.L1 AS L1 FROM (SELECT SUM(t0.PR) AS L0, t0.IdRegister AS L1 FROM (SELECT DISTINCT DataUpload.PR, DataUpload.IdLayerStruct, DataUpload.IdRegister, DataUpload.Hash, STR_TO_DATE(DataUpload.DateReg,'%Y-%m-%d') AS Date  FROM DataUpload WHERE DataUpload.Status = 1 /*AND DataUpload.IdRegister = 107*/) AS t0 GROUP BY L1) AS t2 RIGHT JOIN (SELECT SUM(t1.Me) AS L0, t1.IdRegister AS L1 FROM (SELECT DataMIR.ME, DataMIR.IdLayerStruct, DataMIR.Ind, DataMIR.IdRegister FROM DataMIR WHERE DataMIR.Status = 1 /*AND DataMIR.IdRegister = 107*/) AS t1 GROUP BY L1) AS t3 ON t2.L1 = t3.L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE Registers.Status = 1 ORDER BY L2 DESC");
		
		$res = $this->bConnection->query("SELECT SUM(tB) AS L2, SUM(tA) AS L4, tC AS L1 FROM (SELECT (L9*80)/L1 AS tB, 80 AS tA , L14 AS tC FROM (SELECT SUM(t5.L1) AS L1, SUM(t5.L5) AS L9, t5.L14 FROM (SELECT t4.L4 AS L1, t4.L5, Registers.ProjName AS L14 FROM (SELECT t3.L1, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, IF(L5 > L4,L4,L5) AS L5 FROM (SELECT t6.IdRegister AS L1, t6.Ind AS L2, IFNULL(t5.L5,0) AS L5, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 /*AND DataMIR.IdRegister IN (@p)*/) AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.Ind FROM DataMIR WHERE DataMIR.Status = 1 /*AND DataMIR.IdRegister IN (@p)*/ AND DataMIR.Ind != 0) AS t6 ON t5.L2 = t6.Ind AND t5.L1 = t6.IdRegister) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND LayersStruct.IdLayerMethod IN (8)) AS t3 GROUP BY L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE  Registers.Status = 1) AS t5 GROUP BY L14) AS t6 UNION SELECT (L9*20)/L1 AS tB, 20 AS tA, L14  AS tC FROM (SELECT SUM(t5.L1) AS L1, SUM(t5.L9) AS L9, t5.L14 FROM (SELECT t4.L4 AS L1, t4.L5 AS L9, Registers.ProjName AS L14 FROM (SELECT t3.L1, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, IF(L5 > L4,L4,L5) AS L5 FROM (SELECT t6.IdRegister AS L1, IFNULL(t5.L5,0) AS L5, t6.Ind AS L2, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.IdLayerStruct = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 /*AND DataMIR.IdRegister IN (@p)*/) AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.IdLayerStruct AS Ind FROM DataMIR WHERE DataMIR.Status = 1 /*AND DataMIR.IdRegister IN (@p)*/ AND DataMIR.Ind = 0) AS t6 ON t5.L2 = t6.Ind AND t5.L1 = t6.IdRegister) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND LayersStruct.IdLayerMethod IN (3) ) AS t3 GROUP BY L1) AS t4 INNER JOIN Registers ON t4.L1 = Registers.IdRegister WHERE Registers.Status = 1) AS t5 GROUP BY L14) AS t6) AS t7 GROUP BY L1 ORDER BY L2 DESC;");
		
		while($r = mysqli_fetch_assoc($res))
		{
			$ch['data'][] = array('y' => round($r['L2']), 'name' => $r['L1']);
			$chT['data'][] = $r['L1'];
		}
		
		
		$res = $this->bConnection->query("SELECT 'Mujeres' AS L1, SUM(Registers.Fn) AS L2, 'Hombres' AS L3, SUM(Registers.Mn) AS L4 FROM Registers WHERE Registers.Status = 1 GROUP BY L1, L3;");

		while($r = mysqli_fetch_assoc($res))
		{
			//$f = $r['L2']*100/($r['L2'] + $r['L4']);
			//$m = $r['L4']*100/($r['L2'] + $r['L4']);
			//$po['name'] = 'Población total: '.($r['L2'] + $r['L4']);
			$po['data'][] = array('y' => round($r['L2']), 'name' => $r['L1'], 'color' => '#A901DB');
			$po['data'][] = array('y' => round($r['L4']), 'name' => $r['L3'], 'color' => '#E2A9F3');
			
			$ti['text'] = '<label style="color:#ffffff">Total de estudiantes impactados por sexo: '.($r['L2'] + $r['L4']).' individuos</label>';
		}
		
		
		$this->CloseDB();
		$result = array();
		array_push($result,$pr);
		array_push($result,$cr);
		array_push($result,$di);
		array_push($result,$po);
		array_push($result,$diT);
		array_push($result,$crT);
		array_push($result,$prT);
		array_push($result,$ti);
		array_push($result,$ch);
		array_push($result,$chT);
		return json_encode($result, JSON_NUMERIC_CHECK);
    }
	
	
	public function getHomeChartByProject($p)
    {
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');


		$di = array();
		$di['name']  ='Dimensión';
		$di['type'] = 'pie';
		$di['innerSize'] ='50%';
		$di['color'] = '#FF8000';
		$di['tooltip'] = array('valueSuffix' => ' indicadores');
		$diT = array();
		$diT['name'] = 'di';
		$diT['type'] = 'pie';
		
		$cr = array();
		$cr['name'] = 'Cadena de resultados';
		$cr['type'] = 'pie';
		$cr['innerSize'] ='50%';
		$cr['color'] = '#67DC00';
		$cr['tooltip'] = array('valueSuffix' => ' indicadores');
		$crT = array();
		$crT['name'] = 'cr';
		$crT['type'] = 'pie';
		
		$pr = array();
		
		$pr['type'] = 'pie';
		$pr['color'] = '#8000FF';
		$pr['innerSize'] ='50%';
		$pr['tooltip'] = array('valueSuffix' => ' proyectos');
		$prT = array();
		$prT['name'] = 'Lineas de acción';
		$prT['type'] = 'pie';
		
		$ti = array();
		$ti['title'] = 'Titulo';
		
		$po = array();
		$po['name'] = 'Población';
		$po['type'] = 'pie';
		$po['innerSize'] ='50%';
		//$po['showInLegend'] = 'false';
		//$po['dataLabels'] = array('enabled' => false);
		$po['tooltip'] = array('valueSuffix' => ' %');

		//$res = $this->bConnection->query("SELECT Problems.Problem AS L1, COUNT(Registers.IdRegister) AS L2 FROM Registers INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Problems.Status = 1 AND SubProblems.Status = 1 AND Registers.Status = 1 AND Registers.IdRegister = ".$p." GROUP BY L1;");
		$res = $this->bConnection->query("SELECT Problems.Problem AS L99, 'PEM' AS L1, COUNT(Registers.IdRegister) AS L2 FROM Registers INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Problems.Status = 1 AND SubProblems.Status = 1 AND Registers.Status = 1 AND Registers.IdRegister = ".$p." GROUP BY L1 UNION SELECT Problems.Problem AS L99, 'Total de proyectos' AS L1, COUNT(Registers.IdRegister) AS L2 FROM Registers INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Problems.Status = 1 AND SubProblems.Status = 1 AND Registers.Status = 1 AND Problems.Problem = (SELECT Problems.Problem AS L1 FROM Registers INNER JOIN SubProblems ON Registers.IdSubProblem = SubProblems.IdSubProblem INNER JOIN Problems ON SubProblems.IdProblem = Problems.IdProblem WHERE Problems.Status = 1 AND SubProblems.Status = 1 AND Registers.Status = 1 AND Registers.IdRegister = ".$p." GROUP BY L1) GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$pr['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$prT['data'][] = $r['L1'];
			$pr['name'] = 'Línea de acción: '.$r['L99'];
		}
		
		$res = $this->bConnection->query("SELECT ChainResults.ChainResult AS L1, COUNT(DataMIR.IdDataMIR) AS L2 FROM DataMIR INNER JOIN ChainResults ON DataMIR.IdChainResult = ChainResults.IdChainResult WHERE DataMIR.Status = 1 AND ChainResults.Status = 1 AND DataMIR.IdRegister = ".$p." GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$cr['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$crT['data'][] = $r['L1'];
		}
		
		$res = $this->bConnection->query("SELECT UnitsDimension.Dimension AS L1, COUNT(DataMIR.IdDataMIR) AS L2 FROM DataMIR INNER JOIN UnitsDimension ON DataMIR.IdUnitDimension = UnitsDimension.IdUnitDimension WHERE DataMIR.Status = 1 AND UnitsDimension.Status = 1 AND DataMIR.IdRegister = ".$p." GROUP BY L1;");
		while($r = mysqli_fetch_assoc($res))
		{
			$di['data'][] = array('y' => $r['L2'], 'name' => $r['L1']);
			$diT['data'][] = $r['L1'];
		}
		
		$res = $this->bConnection->query("SELECT 'Mujeres' AS L1, SUM(Registers.Fn) AS L2, 'Hombres' AS L3, SUM(Registers.Mn) AS L4, Registers.ProjName AS L5 FROM Registers WHERE Registers.Status = 1 AND Registers.IdRegister = ".$p." GROUP BY L1, L3;");
		while($r = mysqli_fetch_assoc($res))
		{
			$f = $r['L2']*100/($r['L2'] + $r['L4']);
			$m = $r['L4']*100/($r['L2'] + $r['L4']);
			//$po['name'] = 'Población total: '.($r['L2'] + $r['L4']);
			$po['data'][] = array('y' => round($r['L2']), 'name' => $r['L1'], 'color' => '#FF4000');
			$po['data'][] = array('y' => round($r['L4']), 'name' => $r['L3'], 'color' => '#04B404');
			$ti['text'] = '<label style="color:#000000">Total de estudiantes por sexo: '.$r['L2'].' mujeres y '.$r['L4'].' hombres, con '.($r['L2'] + $r['L4']).' personas impactadas</label>';
			//$ti['text'] = '<label style="color:#000000">Total de mujeres y hombres en el proyecto de LxM: '.($r['L2'] + $r['L4']).' individuos</label>';
		}
		
		$this->CloseDB();
		$result = array();
		array_push($result,$pr);
		array_push($result,$cr);
		array_push($result,$di);
		array_push($result,$po);
		array_push($result,$diT);
		array_push($result,$crT);
		array_push($result,$prT);
		array_push($result,$ti);
		return json_encode($result, JSON_NUMERIC_CHECK);
    }
	
	
	public function setCommentsByEvi($strRevDE, $iHash)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		
		
		///////////////////////////////7
		$res = $this->bConnection->query("SELECT Users.IdUser AS L1 FROM Users WHERE Users.User = '".$_SESSION['nme']."' AND Users.Status = 1");
        $row = $res->fetch_assoc();
        $strR = $row['L1'];
		
		/////////////////////
		
		/*if($_SESSION['pfl'] == 7)
		{
			$res = $this->bConnection->query("UPDATE DataUpload SET Rev2 = ".$strR.", RevDE2='".$strRevDE."', RevDate2=NOW(), RevF2 = 1 WHERE DataUpload.Hash = '".$iHash."' AND DataUpload.Status = 1 ;");
		}
		else*/
		$resp = 0;
		if($_SESSION['pfl'] == 6)
		{
			$res = $this->bConnection->query("UPDATE DataUpload SET Rev1 = ".$strR.", RevDE1='".$strRevDE."', RevDate1=NOW(), RevF1 = 1 WHERE DataUpload.Hash = '".$iHash."' AND DataUpload.Status = 1 ;");
			
			$res = $this->bConnection->query("SELECT Users.Email AS L1, Users.User AS L2 FROM DataUpload INNER JOIN Registers ON DataUpload.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE DataUpload.Status = 1 AND DataUpload.Hash = '".$iHash."' AND Registers.Status = 1 AND Users.Status = 1");
			$row = $res->fetch_assoc();
			$resp = $row['L1'].':'.$row['L2'];
		}
		else if($_SESSION['pfl'] == 2)
		{
			$res = $this->bConnection->query("UPDATE DataUpload SET Rev2 = ".$strR.", RevDE2='".$strRevDE."', RevDate2=NOW(), RevF2 = 1 WHERE DataUpload.Hash = '".$iHash."' AND DataUpload.Status = 1;");
		}
		
		
        
		
		
        $this->CloseDB();
        return $resp;
		//return "UPDATE DataUpload SET Rev2 = ".$strR.", RevDE2='".$strRevDE."', RevDate2=NOW() WHERE DataUpload.Hash = '".$iHash."' AND DataUpload.Status = 1;";

    }
	
	public function getScheduleByInd($Id, $Profile, $p)
	{
		$this->OpenDB();
		//$this->bConnection->set_charset('utf8');
		
		if($Profile == 1 || $Profile == 5)
		{
			
			//colores por usuario
			//$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, Schedules.Text AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1 AND Users.IdUser=".$Id);
			
			//colores por componente
			
			$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, Schedules.Text AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN EventsType ON Schedules.IdEventType = EventsType.IdEventType INNER JOIN HexColors ON EventsType.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1 AND Users.IdUser=".$Id);
			
		}
		else if($Profile == 2)
		{
			//colores por usuario
			//con ubactividades
			//$res = $this->bConnection->query("SELECT SubActivities.IdEvent AS id, SubActivities.Date AS start_date, SubActivities.Date AS end_date, CONCAT(Users.User, '@',SubActivities.Val) AS text, HexColors.Color AS color,  '#ffffff' AS textColor FROM SubActivities INNER JOIN Registers ON SubActivities.IdRegister = Registers.IdRegister INNER JOIN Users ON Registers.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE SubActivities.Status = 1 UNION SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1");
			//sin subactividades
			//$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN HexColors ON Users.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1");
			
	
			//colores por componente
			$res = $this->bConnection->query("SELECT Schedules.IdEvent AS id, Schedules.Start AS start_date, Schedules.End AS end_date, CONCAT(Users.User, '@' ,Schedules.Text) AS text, HexColors.Color AS color, '#ffffff' AS textColor FROM Schedules INNER JOIN Users ON Schedules.IdUser = Users.IdUser INNER JOIN EventsType ON Schedules.IdEventType = EventsType.IdEventType INNER JOIN HexColors ON EventsType.IdHexColor = HexColors.IdHexColor WHERE Schedules.Status = 1 AND Users.Status = 1 AND HexColors.Status=1");
		}
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map('utf8_encode', $r);
		}
		$this->CloseDB();
		return json_encode($rows);
		//return $res;
	}
	
	
	public function getLayersStructBySche($p)
	{
		
		//$fu = array();
		//$iLimit = 95;
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT '0' AS key1, 'Selecciona tu indicador...' AS label, 0 AS L1  UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN (SELECT t4.L6, t4.L4 AS L1, t4.L5 AS L2 FROM (SELECT t3.L1, t3.L6, SUM(t3.L4) AS L4, SUM(t3.L5) AS L5 FROM (SELECT t2.L1, t2.L4, t2.L5, LEFT(LayersStruct.Code,2) AS L6 FROM (SELECT t6.IdRegister AS L1, IFNULL(t5.L5,0) AS L5, t6.Ind AS L2, t6.ME AS L4 FROM (SELECT t1.L1, t1.L2, t1.L4, SUM(t1.L5) AS L5 FROM (SELECT DISTINCT DataMIR.IdRegister AS L1, DataUpload.IdLayerStruct AS L2, DataUpload.Hash AS L3, DataMIR.ME AS L4, IFNULL(DataUpload.PR,0) AS L5 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct WHERE DataMIR.Status = 1 AND LENGTH(DataUpload.Hash) != 0 AND DataUpload.Status = 1 AND DataMIR.IdRegister = ".$p.") AS t1 GROUP BY L1, L2, L4) AS t5 RIGHT JOIN (SELECT DataMIR.IdRegister, DataMIR.ME, DataMIR.Ind FROM DataMIR WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND DataMIR.Ind != 0) AS t6 ON t5.L2 = t6.Ind) AS t2 INNER JOIN LayersStruct ON t2.L2 = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 ) AS t3 GROUP BY L1,L6) AS t4 WHERE (L5*100/L4) > ".$this->iLimit.") AS t5 ON LayersStruct.Code = t5.L6 WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC;");
		//$res = $this->bConnection->query("SELECT '0' AS key1, 'Selecciona tu indicador...' AS label, 0 AS L1  UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN (SELECT t4.*, (t4.L1 - t4.L2) FROM (SELECT SUM(t3.L1) AS L1, SUM(t3.L2) AS L2, t3.L6 AS L6 FROM (SELECT DISTINCT DataMIR.IdRegister AS L0, DataMIR.ME AS L1, DataUpload.PR AS L2, DataUpload.Hash AS L5, LEFT(LayersStruct.Code,2) AS L6 FROM DataMIR INNER JOIN DataUpload ON DataMIR.IdRegister = DataUpload.IdRegister AND DataMIR.Ind = DataUpload.IdLayerStruct INNER JOIN LayersStruct ON DataUpload.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataUpload.Status = 1  AND DataMIR.IdRegister = ".$p." AND LENGTH(DataUpload.Hash) != 0) AS t3 GROUP BY L6) AS t4 WHERE  ((t4.L2 * 100)/t4.L1) > ".$this->iLimit.") AS t5 ON LayersStruct.Code = t5.L6 WHERE LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC;");
		//$res = $this->bConnection->query("SELECT '0' AS key1, 'Selecciona tu indicador...' AS label, 0 AS L1  UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t2.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct INNER JOIN DataMIR AS t2 ON t2.Ind = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t2.Status = 1 AND t2.IdRegister = ".$p." UNION SELECT LayersStruct.IdLayerStruct AS key1, CONCAT(LayersStruct.Code , ' - ' , t1.NI) AS label, LayersStruct.OrderMML AS L1 FROM LayersStruct inner join DataMIR AS t1 ON t1.IdLayerStruct = LayersStruct.IdLayerStruct where LayersStruct.Status = 1 AND LayersStruct.OrderMML >0 AND t1.Status = 1 AND t1.IdRegister = ".$p." AND LayersStruct.IdLayerMethod != 4 ORDER BY L1 ASC");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			//$rows[] = array_map(null, $r);
			$rows[] = array('key' => $r['key1'], 'label' => $r['label']);
		}
		
		/*$fu[0] = $rows;
		
		$res = $this->bConnection->query("SELECT '0' AS key1, 'Selecciona tu detalle...' AS label UNION SELECT ScheDetails.IdScheDetail AS key1, ScheDetails.ScheDetail AS label FROM ScheDetails");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			
			$rows[] = array('key' => $r['key1'], 'label' => $r['label']);
		}
		
		$fu[1] = $rows;
		*/
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function getInfo($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
        /*$res = $this->bConnection->query("SELECT Registers.ProjName AS L1, Users.User AS L2 FROM Registers INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Registers.IdRegister = ".$p);*/
		
		$res = $this->bConnection->query("SELECT t1.L1, t1.L2, t2.L3 FROM (SELECT Registers.ProjName AS L1, Users.User AS L2, 1 AS L4 FROM Registers INNER JOIN Users ON Registers.IdUser = Users.IdUser WHERE Registers.Status = 1 AND Users.Status = 1 AND Registers.IdRegister = ".$p.") AS t1 INNER JOIN (SELECT SUM(Registers.Student) AS L3, 1 AS L4 FROM Registers WHERE Registers.Status = 1) AS t2 ON t1.L4 = t2.L4");
        $row = $res->fetch_assoc();
        $strR = $row['L1'].':'.$row['L2'].':'.$row['L3'];
        $this->CloseDB();
        return $strR;
		//return 'El proyecto:El usuario';
    }
	
	
	public function getImageURL()
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		//$res = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS L1, DataUpload.Name AS L2, DataUpload.b64 AS L3, DataUpload.Data AS L4 FROM DataUpload WHERE DataUpload.Status = 1 AND DataUpload.Status = 1 AND ISNULL(DataUpload.b64) = 1;");
		$res = $this->bConnection->query("SELECT DataUpload.IdDataUpload AS L1, DataUpload.Name AS L2, DataUpload.b64 AS L3, DataUpload.Data AS L4 FROM DataUpload WHERE DataUpload.Status = 1 AND DataUpload.Status = 1 AND DataUpload.IdDataUpload = 312;");
        $rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
    }
	
	public function setImageURL($id, $b64)
	{
		$a = json_decode($json, true);
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("UPDATE DataUpload SET b64= '".$b64."' WHERE DataUpload.Status = 1 AND DataUpload.IdDataUpload = 308");
		$this->CloseDB();
		return $res;
	}
	
	public function updateComments($hash, $de)
	{
		$a = json_decode($json, true);
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("UPDATE DataUpload SET DE='".str_replace("'"," ",$de)."' WHERE DataUpload.Hash = '".$hash."'");
		$this->CloseDB();
		return $res;//;"UPDATE DataUpload SET DE='".$de."' WHERE DataUpload.Hash = '".$hash."'";
	}
	
	
	public function setChartComments($p, $txt, $i)
	{
		$this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT Users.IdUser AS L1 FROM Users WHERE Users.Email='".$_SESSION['usr']."' AND Users.Status = 1 AND Users.IdProfile IN (2,6) ");
		$row = $res->fetch_assoc();
		$usr = $row['L1'];
		$res = $this->bConnection->query("UPDATE ChartComments SET Status = 2 WHERE IdRegister =".$p." AND Chart =".$i.";");
		$res = $this->bConnection->query("INSERT INTO ChartComments (IdRegister, IdUser, Data, Chart) VALUES (".$p.", ".$usr.", '".$txt."', ".$i.");");
		$this->CloseDB();
        return $res;
	}
	
	
	public function getChartComments($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT ChartComments.Data AS L1, ChartComments.Chart AS L2 FROM ChartComments WHERE ChartComments.IdRegister = ".$p." AND ChartComments.Status = 1");
        $rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
    }
	
	public function getProjectDetails($p)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT t2.Code AS L1, IFNULL(t2.NI,'N/A') AS L2, IFNULL(t2.ME,0) AS L3, IFNULL(t3.PR,0) AS L4, IF(t3.PR >= t2.ME,t2.ME,IFNULL(t3.PR,0)) AS L5, t2.L0, t2.OrderMML AS L6, IFNULL(t2.Unit,'N/A') AS L7 FROM (SELECT LayersStruct.Code, DataMIR.NI, DataMIR.ME, LayersStruct.OrderMML, DataMIR.Unit, LayersStruct.IdLayerStruct, 1 AS L0 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.IdLayerStruct = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND LayersStruct.IdLayerMethod IN (3) UNION SELECT LayersStruct.Code, DataMIR.NI, DataMIR.ME, LayersStruct.OrderMML, DataMIR.Unit, LayersStruct.IdLayerStruct, 0 AS L0 FROM DataMIR INNER JOIN LayersStruct ON DataMIR.Ind = LayersStruct.IdLayerStruct WHERE DataMIR.Status = 1 AND DataMIR.IdRegister = ".$p." AND LayersStruct.Status = 1 AND LayersStruct.IdLayerMethod IN (8)) AS t2 LEFT JOIN ( SELECT t1.IdLayerStruct, SUM(t1.PR) AS PR FROM ( SELECT DISTINCT DataUpload.IdLayerStruct, DataUpload.PR, DataUpload.Hash FROM DataUpload WHERE DataUpload.Status = 1 AND DataUpload.IdRegister = ".$p.") AS t1 GROUP BY IdLayerStruct) AS t3 ON t2.IdLayerStruct = t3.IdLayerStruct ORDER BY L6;");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
    }
	
	public function getScheAgenda($p)
	{
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT t1.L1, t1.L2, t1.L3 FROM (SELECT Registers.ProjName, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS L1, LayersStruct.Code AS L2, DataMIR.NI AS L3 FROM Registers INNER JOIN Schedules ON Registers.IdRegister = Schedules.IdRegister INNER JOIN LayersStruct ON Schedules.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON Registers.IdRegister = DataMIR.IdRegister AND LayersStruct.IdLayerStruct = DataMIR.Ind WHERE DataMIR.Status = 1 AND LayersStruct.Status = 1 AND Registers.Status = 1 AND Registers.IdRegister = ".$p." AND Schedules.Status = 1 AND Schedules.End >= NOW() AND LayersStruct.IdLayerMethod IN (8) UNION SELECT Registers.ProjName, STR_TO_DATE(Schedules.End,'%Y-%m-%d') AS L1, LayersStruct.Code AS L2, DataMIR.NI AS L3 FROM Registers INNER JOIN Schedules ON Registers.IdRegister = Schedules.IdRegister INNER JOIN LayersStruct ON Schedules.IdLayerStruct = LayersStruct.IdLayerStruct INNER JOIN DataMIR ON Registers.IdRegister = DataMIR.IdRegister AND LayersStruct.IdLayerStruct = DataMIR.IdLayerStruct WHERE DataMIR.Status = 1 AND LayersStruct.Status = 1 AND Registers.Status = 1 AND Registers.IdRegister = ".$p." AND Schedules.Status = 1 AND Schedules.End >= NOW() AND LayersStruct.IdLayerMethod IN (3)) AS t1 ORDER BY L1, L2;");
		$rows = array();
		while($r = mysqli_fetch_assoc($res)) {
			$rows[] = array_map(null, $r);
		}
		$this->CloseDB();
		return json_encode($rows);
	}
	
	public function setDirectory($json)
	{
		$r = json_decode($json, true);
		$this->OpenDB();
		$this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("INSERT INTO Directory (Name, Website, Address, Tel, Email, Services, InCharge, Shift, Cost, Contact, Category, Map) VALUES ('".$r['L1']."','".$r['L2']."','".$r['L3']."','".$r['L4']."','".$r['L5']."','".$r['L6']."','".$r['L7']."','".$r['L8']."','".$r['L9']."','".$r['L10']."','".$r['L11']."', '".str_replace("'","\"",$r['L12'])."')");
		$this->CloseDB();
		return $res;
		//return "INSERT INTO Directory (Name, Website, Address, Tel, Email, Services, InCharge, Shift, Cost, Contact, Category, Map) VALUES ('".$r['L1']."','".$r['L2']."','".$r['L3']."','".$r['L4']."','".$r['L5']."','".$r['L6']."','".$r['L7']."','".$r['L8']."','".$r['L9']."','".$r['L10']."','".$r['L11']."', '".$r['L12']."')";
	}
	
	public function getDirectory($category)
    {
        $this->OpenDB();
        $this->bConnection->set_charset('utf8');
		$res = $this->bConnection->query("SELECT IdDirectory AS L0, Name AS L1, Website AS L2, Address AS L3, Tel AS L4, Email AS L5, Services AS L6, InCharge AS L7, Shift AS L8, Cost AS L9, Contact AS L10, IFNULL(Map,'sin mapa') AS L11 FROM Directory WHERE Directory.Status = 1 AND Directory.Category = '".$category."';");
        $rows = array();
		while($r = mysqli_fetch_assoc($res)) {

			$rows[] = array_map(null,$r);
		}
		$this->CloseDB();
		return json_encode($rows);
    }
	
	
    
}

?>