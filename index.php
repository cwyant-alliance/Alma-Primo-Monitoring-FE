<html>
<head>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Raleway:900" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>
<script src="scripts.js"></script>
</head>

<body>
  <div class="container">
    <div class="logo">
    <h1>Alma/Primo Monitoring</h1>
  </div>
<?php

// date formatting
function pdate($date) {
    $date = new DateTime($date);
    $date->setTimezone(new DateTimeZone('America/Los_Angeles'));
    return $date->format('m/d/y h:ia');
  };

// read from protractor Primo XML file
$xml = simplexml_load_file("data/pbo.xml") or die("Error: Cannot create object");
$log = simplexml_load_file("data/log.xml") or die("Error: Cannot create object");

      // check for any failures
      $failures = 0;
      $failures = count($xml->xpath('//testcase/failure'));

      // adjust formatting if failures exist
      if ($failures > 0) {
          $pboCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <strong>Primo Back Office <i class="fas fa-times"></i></strong></button>';
      }
      else {
        $pboCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <strong>Primo Back Office <i class="fas fa-check"></i></strong></button>';
      }

?>

<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0"><?php echo $pboCardFormat; ?>
      </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <table class="table">
          <thead>
           <tr>
             <th scope="col">Task</th>
             <th scope="col">Status</th>
           </tr>
         </thead>
          <tbody>

          <?php
          foreach ($xml->testsuite as $suite) {
                echo '<tr class="bg-light"><td><strong>' . $suite['name'] . ' - last updated: ' . pdate($suite['timestamp']) . '</strong></td><td></td></tr>';

                if ($suite['name'] == 'check CP_Alma last run') {
                  echo '<tr><td>Start time: ' . pdate($log->cp_alma->start) . '<br/>End time: ' . pdate($log->cp_alma->end) . '<td></td></td></tr>';
                }

                if ($suite['name'] == 'check Dedup_Frbr last run') {
                  echo '<tr><td>Start time: ' . pdate($log->dedup->start) . '<br/>End time: ' . pdate($log->dedup->end) . '<td></td></td></tr>';
                }

                if ($suite['name'] == 'check Indexing_and_Hotswapping last run') {
                  echo '<tr><td>Start time: ' . pdate($log->indexing->start) . '<br/>End time: ' . pdate($log->indexing->end) . '<td></td></td></tr>';
                }

              foreach($suite->testcase as $case) {
                  if ($case->failure) {
                    $failures++;
                    $status = '<td><button type="button" class="btn btn-danger" data-toggle="popover" data-content="' . $case->failure['message'] . '">Failed</button></td>';
                  }

                  else {
                      $status = '<td><button type="button" class="btn btn-success" disabled>Ok</button></td>';
                  }

                echo '<tr><td>' . $case['classname'] . ' (' . $case['name'] . ')</td>' . $status . '</tr>';
              };

        }; ?>
         </tbody>
       </table>

      </div>
    </div>
  </div> <!-- PBO card -->

<?php

  // read from protractor Primo XML file
  $xml = simplexml_load_file("data/pfe.xml") or die("Error: Cannot create object");

        // check for any failures
        $failures = 0;
        $failures = count($xml->xpath('//testcase/failure'));

        // adjust formatting if failures exist
        if ($failures > 0) {
            $pfeCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
            <strong>Primo Front End <i class="fas fa-times"></i></strong></button>';
        }
        else {
          $pfeCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
          <strong>Primo Front End <i class="fas fa-check"></i></strong></button>';
        }

  ?>


  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0"><?php echo $pfeCardFormat; ?>
      </h5>
    </div>

    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        <table class="table">
          <thead>
           <tr>
             <th scope="col">Task</th>
             <th scope="col">Status</th>
           </tr>
         </thead>
          <tbody>

          <?php
          foreach ($xml->testsuite as $suite) {
                echo '<tr class="bg-light"><td><strong>' . $suite['name'] . ' - last updated: ' . pdate($suite['timestamp']) . '</strong></td><td></td></tr>'; ?>

              <?php
              foreach($suite->testcase as $case) {
                  if ($case->failure) {
                    $failures++;
                    $status = '<td><button type="button" class="btn btn-danger" data-toggle="popover" data-content="' . strip_tags($case->failure['message']) . '">Failed</button></td>';
                  }

                  else {
                      $status = '<td><button type="button" class="btn btn-success" disabled>Ok</button></td>';
                  }

                echo '<tr><td>' . $case['classname'] . ' (' . $case['name'] . ')</td>' . $status . '</tr>';
              };

        }; ?>
         </tbody>
       </table>

      </div>
    </div>
  </div> <!-- PFE card -->


<!-- Alma -->

<?php

$npjxml = simplexml_load_file("data/npj.xml") or die("Error: Cannot create object");
$oclcnewxml = simplexml_load_file("data/oclcnew.xml") or die("Error: Cannot create object");
$oclcupdatesxml = simplexml_load_file("data/oclcupdates.xml") or die("Error: Cannot create object");

      // check for any failures
      $failures = 0;

      foreach($npjxml->job_instance as $instance) {
        if ($instance->status != 'COMPLETED_SUCCESS') {
          $failures++;
        }
      };

      foreach($oclcnewxml->job_instance as $instance) {
        if ($instance->status != 'COMPLETED_SUCCESS') {
          $failures++;
        }
      };

      foreach($oclcupdatesxml->job_instance as $instance) {
          if ($instance->status != 'COMPLETED_SUCCESS') {
            $failures++;
          }
        };

      // adjust formatting if failures exist
      if ($failures > 0) {
          $almaCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <strong>Alma <i class="fas fa-times"></i></strong></button>';
      }
      else {
        $almaCardFormat = '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <strong>Alma <i class="fas fa-check"></i></strong></button>';
      }

?>

  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <?php echo $almaCardFormat; ?>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <h3>Network Publishing Job</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Start Time</th>
              <th scope="col">End Time</th>
              <th scope="col">Progress</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($npjxml->job_instance as $instance) {
              if ($instance->progress < 100) {
                $progress = 'bg-danger';
              }
              else {
                $progress = 'bg-success';
              }

              echo '<tr><td>' . pdate($instance->start_time) . '</td><td>' . pdate($instance->end_time) . '</td><td><div class="progress">
              <div class="progress-bar ' . $progress . '"role="progressbar" style="width: ' . $instance->progress . '%;" aria-valuenow="'. $instance->progress .'" aria-valuemin="0" aria-valuemax="100">' . $instance->progress . '%</div>
              </div></td><td>' . $instance->status . '</td></tr>';
            }; ?>

          </tbody>
        </table>

        <h3>OCLC New Bibs Jobs</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Start Time</th>
              <th scope="col">End Time</th>
              <th scope="col">Progress</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($oclcnewxml->job_instance as $instance) {
              if ($instance->name == 'Metadata Import: OCLC New Bibs (FTP)') {
                  if ($instance->progress < 100) {
                    $progress = 'bg-danger';
                  }
                  else {
                    $progress = 'bg-success';
                  } // if 2

                  echo '<tr><td>' . pdate($instance->start_time) . '</td><td>' . pdate($instance->end_time) . '</td><td><div class="progress">
                  <div class="progress-bar ' . $progress . '"role="progressbar" style="width: ' . $instance->progress . '%;" aria-valuenow="'. $instance->progress .'" aria-valuemin="0" aria-valuemax="100">' . $instance->progress . '%</div>
                  </div></td><td>' . $instance->status . '</td></tr>';
            } // if 1
            }; ?>

          </tbody>
        </table>

            <h3>OCLC Updated Bibs Jobs</h3>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Start Time</th>
                  <th scope="col">End Time</th>
                  <th scope="col">Progress</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($oclcupdatesxml->job_instance as $instance) {
                  if ($instance->name == 'Metadata Import: OCLC Updated Bibs (FTP)') {
                    if ($instance->progress < 100) {
                      $progress = 'bg-danger';
                    }
                    else {
                      $progress = 'bg-success';
                    } // if 2

                    echo '<tr><td>' . pdate($instance->start_time) . '</td><td>' . pdate($instance->end_time) . '</td><td><div class="progress">
                    <div class="progress-bar ' . $progress . '"role="progressbar" style="width: ' . $instance->progress . '%;" aria-valuenow="'. $instance->progress .'" aria-valuemin="0" aria-valuemax="100">' . $instance->progress . '%</div>
                    </div></td><td>' . $instance->status . '</td></tr>';
                } // if 1
                }; ?>

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>

</div> <!-- container -->
</body>
</html>
