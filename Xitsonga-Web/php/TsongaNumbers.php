<?php
    /**
     * Numbers in Tsonga writen words
     * 
     * @Author Sneidon Dumela <sneidon@tsongaonline.co.za>
     * @version 1.0
     */
    class TsongaNumbers {
        /**
         * 
         * @param int $aNumber
         * @return string
         */
        public function getNumberInTsonga($aNumber){

          if($aNumber < 0){
            $aNumber = 10000;
          }

          if($aNumber < 100){
            return $this->getTens($aNumber);
          }elseif($aNumber < 1000){
            return $this->getHundreds($aNumber);
          }elseif($aNumber < 10000){
            return $this->getThousands($aNumber);
          }else{
            return "Your number is not within the range (0 - 9999)";
          }
        }
        /**
         * 
         * @param string $aNumber
         * @return string
         */
        private function getTens($aNumber){
            $aNumber.="";
            $aGeneral = Array("noto","n'we","mbirhi","nharhu","mune","ntlhanu","ntsevu","nkombo","nhungu","nkaye","khume");

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
         * @param string $aNumber
         * @return string
         */
        private function getHundreds($aNumber){
            $aNumber.="";
            if($aNumber % 100 == 0){
                if($aNumber == 100){
                  return "dzana";
                }else{
                  return "madzana ".$this->getTens($aNumber[0]);
                }
            }else{
               if($aNumber < 200){
                  if($aNumber[1] > 0){
                      return "dzana ".$this->getTens($aNumber[1].$aNumber[2]);
                  }else{
                      return "dzana ".$this->getTens($aNumber[2]);
                  }
               }else{
                    if($aNumber[1] > 0){
                          return "madzana ".$this->getTens($aNumber[0]). " ".$this->getTens($aNumber[1].$aNumber[2]);
                    }else{
                          return "madzana ".$this->getTens($aNumber[0]). " ".$this->getTens($aNumber[2]);
                    }
               }
            }
        }
        /**
         * 
         * @param string $aNumber
         * @return string
         */
        private function getThousands($aNumber){
            $aNumber.="";
            if($aNumber % 1000 == 0){
                if($aNumber == 1000){
                   return "gidi";
                }else{
                    return "magidi ".$this->getTens($aNumber[0]);
                }
            }
            else{
                if($aNumber < 2000){
                    if($aNumber[1] == 0 AND $aNumber[2] != 0){
                        return "gidi "." ".$this->getTens($aNumber[2].$aNumber[3]);
                    }elseif($aNumber[1] == 0 AND $aNumber[2] == 0){
                      return "gidi ". " ".$this->getTens($aNumber[3]);
                    }else{
                      return "gidi ". " ".$this->getHundreds($aNumber[1].$aNumber[2].$aNumber[3]);
                    }
                }else{
                    if($aNumber[1] == 0 AND  $aNumber[2] != 0){
                        return "magidi ".$this->getTens($aNumber[0])." ".$this->getTens($aNumber[2].$aNumber[3]);
                    }elseif($aNumber[1] == 0 AND $aNumber[2] == 0){
                        return "magidi ".$this->getTens($aNumber[0]). " ".$this->getTens($aNumber[3]);
                    }else{
                      return "magidi ".$this->getTens($aNumber[0]). " ".$this->getHundreds($aNumber[1].$aNumber[2].$aNumber[3]);
                    }
                }
            }
        }
    }