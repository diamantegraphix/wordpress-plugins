/*
To eventually become a plugin...

Display the Gallery Pro album containing images that was most recently added

Works with WordPress plugin Gallery Bank Pro Edition (gallery-bank-pro-edition)
https://wordpress.org/plugins/gallery-bank/
http://tech-banker.com/products/wp-gallery-bank/

TODO: See if this works with regular edition
*/


// Return the album id of the most recent album with images
// If no album with images is found, returns false
function get_most_recent_album () {
    global $wpdb;
    
    // Get the album id of the most recent album
    $album_count = $wpdb->get_var
    (
        "SELECT album_id FROM " .gallery_bank_albums(). " order by album_id desc limit 1"
    );

    // Check if most recent album contains images;  if not, check albums in decending 
    // order until album with images is found, or all albums have been checked
    $pics_count == 0;
    do {
    
        // Get number of images in album
        $pics_count = $wpdb->get_var
        (
            $wpdb->prepare
            (
                "SELECT count(".gallery_bank_albums().".album_id) FROM ".gallery_bank_albums()." join ".gallery_bank_pics()." on ".gallery_bank_albums().".album_id =  ".gallery_bank_pics().".album_id where ".gallery_bank_albums().".album_id = %d ",
                $album_count
            )
        );
        
        // If no images in album, move to next album
        if ($pics_count == 0) {
            $album_count--;
        }
    } while (($album_count > 0) && ($pics_count == 0));

    // Return album id if album containing images is found, otherwise return false
    if ($album_count > 0) {
        $most_recent = $album_count;
    } else {
        $most_recent = false;
    }
    return $most_recent;
}
