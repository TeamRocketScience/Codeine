<?php

  function F_Ico2_Process($Data)
  {
      $Pockets = array();
// TODO Title у иконок
      if (preg_match_all('@<icon>(.*)<\/icon>@SsUu', $Data, $Matches))
      {
          $Icos = array_unique($Matches[1]);
          sort($Icos);
          
          $IcoID = sha1(implode('',$Icos));
          $IcoFile = Root.Data.'/_Icons/'.$IcoID.'.png';

          if (!file_exists($IcoFile))
          {
              $IM = imagecreatetruecolor(sizeof($Icos)*16, 16);
              imagealphablending($IM, false);
              imagesavealpha($IM, true);

              foreach ($Icos as $IX => $Ico)
              {
                  if (mb_substr($Ico,0,1) == '~')
                      $URL = EngineShared.'/Images/Icons/'.mb_substr($Ico,1).'.png';
                  else
                      $URL = Root.'/Images/Icons/'.$Ico.'.png';
                  
                  $IM2 = imagecreatefrompng($URL);
                  imagealphablending($IM2, false);
                  imagesavealpha($IM2, true);

                  imagecopy ($IM, $IM2, $IX*16, 0, 0, 0, 16, 16);
              }
              imagepng($IM, $IcoFile);
          }

          Page::CSS('.Icon'.$IcoID.' { display: inline; background-image: url("/Data/_Icons/'.$IcoID.'.png"); }');
          foreach($Icos as $IX => $Ico)
            $Data = str_replace('<icon>'.$Ico.'</icon>', '<div class="Icon'.$IcoID.'" style="background-position: -'.($IX*16).'px top;"><img src="/Images/s.gif" width=16 height=18 /></div>', $Data);
      }

      return $Data;
  }