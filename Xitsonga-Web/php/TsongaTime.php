<?php
   date_default_timezone_set('Africa/Johannesburg');

    /**
     * Entity Manager
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TsongaTime {
        /**
         * @param Array
         * 
         * @return String - Tsonga representation of specified time
         */
        public function getTime($aTime)
        {	
            $aMinute = intval($aTime[1]);

            if($aTime[0] >= 12){$aHour = intval($aTime[0] - 12);}  
            else{ $aHour = intval($aTime[0]);}

            if($aMinute == 0){
                return "Awara ya ".$this->getTens($aHour);
            }elseif($aMinute == 1){
                return "Minete yin'we se ku bile awara ya ".$this->getTens($aHour);
            }elseif($aMinute < 4){
                return "Timinete ti".$this->getTens($aMinute)." ku bile awara ya ".$this->getTens($aHour);
            }elseif($aMinute < 30){
                return $this->getTens($aMinute)." wa timinete ku bile awara ya ".$this->getTens($aHour);
            }elseif($aMinute < 57){
                return $this->getTens(60 - $aMinute)." wa timinete ku nga si ba awara ya ".$this->getTens($aHour + 1);
            }elseif($aMinute < 59){
                return "Timinete ti".$this->getTens(60 - $aMinute)." ku nga si ba awara ya ".$this->getTens($aHour + 1);
            }elseif($aMinute < 62){
                return "Minete yin'we ku nga si ba awara ya ".$this->getTens($aHour + 1);
            }	
        }
        /**
         * 
         * @param Integer - a number
         * @return String - a number in tsonga
         */
        public  function getNumber($aNumber){
            if($aNumber < 100){return $this->getTens($aNumber);}
            else{return "Time is not valid";}
        }
        /**
         * 
         * @param Integer - a number
         * @return String - a number in tsonga
         */
        public function getTens($aNumber){
            $aNumber.="";
            $aGeneral = Array("khume mbirhi","n'we","mbirhi","nharhu","mune","ntlhanu","ntsevu","nkombo","nhungu","nkaye","khume");
            if($aNumber <= 10){
                return $aGeneral[$aNumber];
            }elseif($aNumber < 20){
              return "khume ".$aGeneral[$aNumber[1]];
            }elseif(($aNumber[1] == 0) AND $aNumber < 100 ){
                return "makume ".$aGeneral[$aNumber[0]];
            }elseif($aNumber < 100){
                return "makume ".$aGeneral[$aNumber[0]]. " ".$aGeneral[$aNumber[1]];
            }
        }
        /**
         * 
         * @return String - a number in tsonga
         */
        public function single($aWord){
          return $aGeneral[$aWord];
        }
        /**
         * 
         * @return String - a number in tsonga
        */
        public function currentTime(){
            $aDate = date('y-m-d');
            $aTime = localtime();

            for($i = 2; $i > 0; $i --)
            {
              $aLocaltime .= $aTime[$i] .":";
            }
            $aLocaltime .= $aTime[3];
            $aDate.= " ".$aLocaltime;
            return $aDate;
        }
        /**
         * 
         * @return String - a number in tsonga
        */
        public function returnRealTime($datetime)
        {
            $aTime = explode(' ', $datetime,10);
            $aCurrent = explode(' ', $this->currentTime(),10);

            $aDate = explode('-', $aTime[0],10);
            $aTime =explode(':', $aTime[1],10);
            
            $aTime[0] =  $aTime[0];
            return $aTime;
        }
        /**
         * 
         * @return String - a number in tsonga
        */
        public function returnRealDate($datetime)
        {
            $aTime = explode(' ', $datetime,10);
            $aCurrent = explode(' ', $this->currentTime(),10);

            $aDate = explode('-', $aTime[0],10);
            $aTime =explode(':', $aTime[1],10);

            return $aDate;
        }
    }

?>