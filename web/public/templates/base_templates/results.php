<div class="container container-results">
<?php 
      $results_found = $results['response']['numFound'];
      if ($results_found != 0) {
?>
    <h4 style="color: gray; margin-left:15px; margin-top:0px;margin-bottom:15px"><?php echo $results_found; ?> documents found</h4>
      

  <?php 

  }
  $docs_size = count($results['response']['docs']);
  if($docs_size == 0) {
    echo '<h3 id="no-results"> No results found! </h3>';
  } 
  ?>

  <div class="panel-group" id="results-accordion">

    <?php

    $start = $results['response']['start'];

    for($i = 0; $i < $docs_size; $i++){
      $doc = $results['response']['docs'][$i];
      $ocurrences = 0;

      foreach ($doc as $key => $value) {
        if(substr($key, 0, 6 ) === "count_") {
           $ocurrences += $value;
        }
      }

      $hl = $results['highlighting'][$doc['id']];
      echo '
      <div class="panel panel-default" id="panel'.$i.'"> 
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-target="#collapse'.$i.'" href="">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-10">
                    <div class="row">
                      <p class="col-md-4">Patient: <span class="patient">'.$doc['patient'].'</span></p>
                      <p class="col-md-4">Therapist: <span class="therapist">'.$doc['therapist'].'</span></p>
                    </div>
                    <div class="row">
                      <p class="col-md-4">Session: <span class="session">'.$doc['session_number'].'</span></p>';
                      if(isset($doc['session_date'])) {
                        echo '<p class="col-md-4 date-p">Date: <span class="date">'.substr($doc['session_date'], 0, 10).'</span></p>';
                      }

      echo '
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="row">
                      <p class="result-index">#'.($start + $i + 1).'</p>
                    </div>
                    <div class="row">
                      <!--<p class="caret-span"></p>-->
                      <span class="glyphicon glyphicon-chevron-down caret-span" aria-hidden="true"></span>
                    </div>
                  </div>
                </div>
              </div>     
            </a>
          </h4>
        </div>

        <div id="collapse'.$i.'" class="panel-collapse collapse">
          <div class="panel-body">
            <p class="score">score: '.$doc['score'].'</p>';
            if($ocurrences != 0) {
              echo '<p>'. $ocurrences .' ocurrences found on this file</p>';
            } 
            echo '
            <ul>';
            if(isset($hl['content'])) {
              for($k = 0; $k < count($hl['content']); $k++){
                echo '<li>'.$hl['content'][$k].'</li>';
              }
            }

    echo '  
            </ul>
            <a href="./transcriptions/'.$doc['patient'].'/'.$doc['file'].'" download>Download the document</a>
          </div>
        </div>
      </div>
      ';
    }

    ?>  
  </div>

  <?php 
  $docs_size = count($results['response']['docs']);
  $page_number = ($start / 10 + 1);

   if (isset($origin) && strcmp($origin, "lexical") == 0) {
       if($docs_size != 0) {
          echo '<nav>
            <ul class="pager">';

            $patient_params = "";

            if(isset($_GET['patient']) ) {
              $patient_params .= '&patient='.$_GET['patient'];
            } 

            $paramsNext = $lexical_params_string.'&page='.($page_number + 1).$patient_params;
            $paramsPrev = $lexical_params_string.'&page='.($page_number - 1).$patient_params;

            if($start == 0) {
              echo '<li class="disabled"><a>Previous</a></li>';
            } else {
              echo '<li><a href="http://localhost/lexical?'.$paramsPrev.'">Previous</a></li>';
            }

            if( $page_number >= ceil($results_found / 10)) {
              echo '<li class="disabled"><a>Next</a></li>';
            } else {
              echo '<li><a href="http://localhost/lexical?'.$paramsNext.'">Next</a></li>';
            }

          echo '</ul>
            </nav>';
        } 


   }
   else {

      if($docs_size != 0) {
        echo '<nav>
          <ul class="pager">';

          $patient_params = "";

          if(isset($_GET['patient']) ) {
            $patient_params .= '&patient='.$_GET['patient'];
          } 

          $paramsNext = 'query='.$query.'&page='.($page_number + 1).$patient_params;
          $paramsPrev = 'query='.$query.'&page='.($page_number - 1).$patient_params;

          if($start == 0) {
            echo '<li class="disabled"><a>Previous</a></li>';
          } else {
            echo '<li><a href="http://localhost/search?'.$paramsPrev.'">Previous</a></li>';
          }

          if( $page_number >= ceil($results_found / 10)) {
            echo '<li class="disabled"><a>Next</a></li>';
          } else {
            echo '<li><a href="http://localhost/search?'.$paramsNext.'">Next</a></li>';
          }

        echo '</ul>
          </nav>';
      } 

   }

?>

  

</div>
