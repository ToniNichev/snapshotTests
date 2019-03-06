<?php
 
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverPoint;
 
$composer_dir = '/Users/toninichev/composer';
require_once $composer_dir . '/vendor/autoload.php';
 
$host = 'http://127.0.0.1:4444/wd/hub'; // this is the default port
 
class picClass {
    public $url = "";
    public $diff = array();
}

 
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
 
// Set size
$driver->manage()->window()->setPosition(new WebDriverPoint(0,0));
$driver->manage()->window()->setSize(new WebDriverDimension(1280,800));


function markDifference($src, $scrTwo, $id) {
    $im     = imagecreatefrompng($src);
    $imTwo     = imagecreatefrompng($scrTwo);
    $size   = getimagesize($src);
    $startDrawing = 0;
    $width  = $size[0];
    $height = $size[1];
    $diffsCount = 0;

    $diffs = array();

    file_put_contents($id . ".txt", "Difference detected", FILE_APPEND);

    for($x=0;$x<$width;$x++)
    {
        for($y=0;$y<$height;$y++)
        {
            $rgb = imagecolorat($im, $x, $y);
            $rgbTwo = imagecolorat($imTwo, $x, $y);
            /*
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;         
            */

            if($rgb != $rgbTwo && $startDrawing == 0) {
              //if($startDrawing == 0)
              array_push($diffs, $x, $y);
              file_put_contents($id . ".txt",  "\n" .$x . " " . $y, FILE_APPEND);
              $startDrawing = 1;
              break;
            }
            else {
            }
        }
    }
    
    return $diffs;
}


 
function takeScreenshot($driver, $url, $id) {
    // Navigate to the page
    $driver->get($url);
    
    $driver->wait(3);
    
    // Take a screenshot
    $driver->takeScreenshot(__DIR__ . "/screenshots/scr" . $id . "-tmp.png");
}

//$urls = array('https://www.toni-develops.com/', 'https://www.toni-develops.com/2017/04/27/git-bash-cheatsheet/', 'https://www.toni-develops.com/webpack/', 'https://www.toni-develops.com/algorithms/');
$urls = array('https://www.toni-develops.com/', 'https://www.toni-develops.com/2017/04/27/git-bash-cheatsheet/');
//$urls = array('http://mydev.com/selenium/test-pages/one.html', 'http://mydev.com/selenium/test-pages/two.html');

$urls = array('http://mydev.com/selenium/test-pages/one.html');

$html = '';
$results = array();
$match = 0;
$noMatch = 0;
$picInfo = array();

for($i = 0; $i < count($urls); $i++) {
    takeScreenshot($driver,$urls[$i], $i);   
    $diff = array();
    $a = sha1_file(__DIR__ . "/screenshots/scr" . $i . "-tmp.png");
    $b = file_exists(__DIR__ . "/screenshots/scr" . $i . ".png") ? sha1_file(__DIR__ . "/screenshots/scr" . $i . ".png") : null;
    $className = 'match';
    if($a == $b || $b == null) {
        $match ++;
    }else {
        $diff = markDifference(__DIR__ . "/screenshots/scr" . $i . "-tmp.png", __DIR__ . "/screenshots/scr" . $i . ".png", $i);
        if(count($diff) > 0) {
            $className = 'no-match';
            $noMatch ++;
        }
        else {
           $match ++;
        }
    }

    $picObj = new picClass();
    $picObj->diff = $diff;
    $picObj->url = $urls[$i];

    array_push($picInfo, $picObj);

    array_push($results, '"'.$className . '"');
    rename(__DIR__ . "/screenshots/scr" . $i . ".png", __DIR__ . "/screenshots/scr" . $i . "-old.png");
    rename(__DIR__ . "/screenshots/scr" . $i . "-tmp.png", __DIR__ . "/screenshots/scr" . $i . ".png");
    $html .= '<div class="picWrapper ' .$className . '"><div><input type="test" value="' . $urls[$i] . '" readonly /><button onclick="popitup(' . $i . ')">O</button></div><canvas width="1280" height="677" id="canvas-' . $i . '"/></div>';
}

$resultsArray = implode(',', $results); 

// Close the Chrome browser
$driver->quit();
?>

<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="stylesheet" type="text/css" href="main.css" media="screen">
<script>
    var resultsArray = [<?php echo $resultsArray ?>];
    var match = <?php echo $match ?>;
    var noMatch = <?php echo $noMatch ?>;


    var picInfo = [
        <?php
            foreach($picInfo as $pic) {
                echo "{";
                    echo "url: '" . $pic->url . "',";
                    echo "diffs : [" . implode(',', $pic->diff) . "]";
                echo "},";
            }
            echo "{}";
        ?>
    ];


</script>
</head>
<body>
    <div id="controlPanel">
        <div id="controlPalenInner">
            <div class="controlPanelPiece buttons">
                <button>RUN</button>
            </div>
            <div class="controlPanelPiece">
                <div id="piechart_3d" style="width: 400px; height: 150px;"></div>
            </div>
        </div>
    </div>
    <?php
        echo $html;
    ?>
<script type="text/javascript" src="charts.js"></script>       
<script type="text/javascript" src="arrows.js"></script>   
<script type="text/javascript" src="main.js"></script>        
</body>
</html>

