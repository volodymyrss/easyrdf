<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../lib/');
    require_once "EasyRdf/Graph.php";

    ## Configure the RDF parser to use
    require_once "EasyRdf/ArcParser.php";
    EasyRdf_Graph::setRdfParser( new EasyRdf_ArcParser() );

    ## Add the Google Vocab namespace
    EasyRdf_Namespace::add('gv', 'http://rdf.data-vocabulary.org/#');
    
    $url = $_GET['url'];
?>
<html>
<head><title>Review Extract</title></head>
<body>
<h1>Review Extract</h1>
<form method="get">
<p>Please enter the URL of a page with a review on it (marked up with Google Review RDFa):</p>
<input name="url" type="text" size="48" value="<?= empty($url) ? 'http://www.bbc.co.uk/music/reviews/2n8c.html' : $url ?>" />
<input type="submit" />
</form>
<?php
    if ($url) {
        $graph = new EasyRdf_Graph( $url );
        if ($graph) $review = $graph->firstOfType('gv_Review');
    }
      
    if ($review) {
        echo "<dl>\n";
        # FIXME: support gv_itemreviewed->gv_name ??
        if ($review->first('gv_itemreviewed')) echo "<dt>Item Reviewed:</dt><dd>".$review->first('gv_itemreviewed')."</dd>\n";
        if ($review->first('gv_rating')) echo "<dt>Rating:</dt><dd>".$review->first('gv_rating')."</dd>\n";
        # FIXME: support gv_reviewer->gv_name ??
        if ($review->first('gv_reviewer')) echo "<dt>Reviewer:</dt><dd>".$review->first('gv_reviewer')."</dd>\n";
        if ($review->first('gv_dtreviewed')) echo "<dt>Date Reviewed:</dt><dd>".$review->first('gv_dtreviewed')."</dd>\n";
        if ($review->first('gv_summary')) echo "<dt>Review Summary:</dt><dd>".$review->first('gv_summary')."</dd>\n";
        echo "</dl>\n";

        if ($review->first('gv_description'))
          echo "<div>".$review->first('gv_description')."</div>\n";
    }
?>
</body>
</html>