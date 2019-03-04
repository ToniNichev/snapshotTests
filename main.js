function popitup(id) {
    var url = "http://mydev.com/selenium/snapshotTests/compare.php?id=" + id;
    var windowName = "compare";
    var h = screen.height;
    var w = screen.width;
    newwindow=window.open(url,windowName, 'toolbar=no ,location=0, status=no,titlebar=no,menubar=no,width='+w +',height=' +h);
    if (window.focus) {newwindow.focus()}
    return false;
  }