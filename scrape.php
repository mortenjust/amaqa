<?

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Which products?
// smart watches -wear and pebble
// $asin_list = "B016CKHEFA,B01F28LIDC,B015SUGJFQ,B00LB2ZQ3C,B00OHR4VXY,B01G46UTIG,B01HHE09PM,B01M7MDK5S,B016CKGQWM,B015SUGI2U,B00NC8PMUK,B00UY26C0Q,B016CKHAZO,B00BKEQBI0,B00UXZQF38,B01ABTJG40,B00O0BRXBE,B01FDPVXZC,B0106IS5XY";
$num_pages = 20; // each page has about 3 questions on it

// $asin_list = "B016CKHEFA,B01F28LIDC";
$asin_list = "B00X4WHP5E";

$asins = explode(",", $asin_list); 
$delimitor = ","; // comma-separation for easy pasting into Sheets (paste special > csv)


// building a URL to look like this: https://www.amazon.com/ask/questions/inline/B015SUGJFQ/1
$url = "https://www.amazon.com/ask/questions/inline";

// loop through products, then pages, then questions
foreach ($asins as $asin) {
    $product = get_product_name($asin);

    for ($i = 1; $i <= $num_pages; $i++) {       // pages 
        $full_url = "$url/$asin/$i";
        $questions = get_questions($full_url);
        
        if(is_array($questions)){        
            foreach($questions as $question){
                $csv_line = $question[0] . $delimitor . enquote($question[1]) . $delimitor . enquote($product) . "\n";        
                echo $csv_line;
            }
        }
    }
}

function enquote($s){    
    return '"'.trim($s).'"';
}

function get_product_name($asin){
    $url = "https://www.amazon.com/gp/p13n-shared/faceout-partial?featureId=CombinedShowHideList&reftagPrefix=pd_srecs_cs_241&widgetTemplateClass=PI%3A%3ASoftlinesRecs%3A%3AViewTemplates%3A%3AList%3A%3AShowHide%3A%3ADesktop&imageHeight=250&faceoutTemplateClass=PI%3A%3ASoftlinesRecs%3A%3AViewTemplates%3A%3AProduct%3A%3ADesktop%3A%3ACarouselFaceout&auiDeviceType=desktop&imageWidth=170&productDetailsTemplateClass=PI%3A%3ASoftlinesRecs%3A%3AViewTemplates%3A%3AProductDetails%3A%3ADesktop%3A%3ABrandWithMinPrice&lazyLoadImages=1&asins=$asin&offset=12";
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    if ($post_paramtrs) {
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
    } curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
    curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    $data = curl_exec($c);
    $status = curl_getinfo($c);
    curl_close($c);
    $re = '/<img alt=\\\\\"(.+?)\\\\\"/';
    preg_match_all($re, $data, $results);
    return $results[1][0];
}

function get_questions($url, $post_paramtrs = false) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    if ($post_paramtrs) {
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
    } curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
    curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    $data = curl_exec($c);
    $status = curl_getinfo($c);
    curl_close($c);
    preg_match_all('/<span class="count">(\d)<\/span>.+?forum.+?">(.+?)<\/a>/s', $data, $results);

    foreach ($results[2] as $index => $value) {
        $question = $value;
        $votes = $results[1][$index];

        $questions[$index] = [$votes, $question];
    }    
    return($questions);

} 
?>