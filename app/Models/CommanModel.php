<?php

namespace App\Models;

use CodeIgniter\Model;

class CommanModel extends Model
{
    protected $dbs;
    public function __construct()
    {
        $this->dbs = \Config\Database::connect();
    }

    public function get_selected_data(String $select, String $table, array $where = [], array $other = []): array
    {
        $db = $this->dbs->table($table);
        $db->select($select);
        if (!empty($where)) {
            $db->where($where);
        }
        if (!empty($other)) {
            foreach ($other as $key => $tbl) {
                    $this->matchval($key,$tbl,$db);
            }
        }
        $query = $db->get();
        return $query->getResult();
    }
    public function get_selected_data_limit(String $select, String $table, array $where = [], array $other = []): array
    {
        $db = $this->dbs->table($table);
        $db->select($select);
        $db->limit(3);
        if (!empty($where)) {
            $db->where($where);
        }
        if (!empty($other)) {
            foreach ($other as $key => $tbl) {
                    $this->matchval($key,$tbl,$db);
            }
        }
        $query = $db->get();
        return $query->getResult();
    }

    public  function update_all($table,array $data,String $val)
    {
       
         $this->dbs->table($table)->updateBatch($data,$val);
    }
    public function update_data($table, $cont, $data)
    {
        $db = $this->dbs->table($table);
        if ($cont)
            $db->where($cont);
        $db->update($data);
    }
    public function delete_data($table, $cont)
    {
        $db = $this->dbs->table($table);
        if ($cont)
            $db->where($cont);
        $db->delete();
    }

    public function insert_data($table, $data)
    {
       
        $db = $this->dbs->table($table);
        $db->insert($data);
        return $this->dbs->insertID();
    }
    function encrypt($plainText)
    {
        $key = "cllit@2019";
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    function decrypt($encryptedText)
    {
        $key = "cllit@2019";
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }
    function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }
    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

  
    public function get_a_field(String $select, String $table, array $contindex): String
    {
        $db = $this->dbs->table($table);
        $db->select($select);

        if (!empty($contindex)) {
            $db->where($contindex);
        }
        $query = $db->get();

        if (!empty($query->getRow()->$select)) {
            return  $query->getRow()->$select;
        } else {
            return "";
        }
    }

    function matchval($key,$arr,$db)
    {
        return  match ($key) {
            'order' => $db->orderBy("$arr[0]","$arr[1]"),
            'like' => $db->like("$arr[0]","$arr[1]"),
            'group' => $db->groupBy("$arr[0]"),
            'limit' => $db->limit("$arr[0]","$arr[1]"),
           
          
        };
    }
    function joinval($arr,$db)
    {
        return $db->join("$arr[0]","$arr[1]");
    }
    public function jointbl(String $select, String $master, array $table, array $where, array $other): array
    {
        $db = $this->dbs->table($master);
        $db->select($select);

        foreach ($table as $tbl) {

            $val = explode(",", $tbl);

            $db->join("$val[0]", "$val[1]");
        }

        if (!empty($where)) {
            $db->where($where);
        }
        if (!empty($other)) {
            foreach ($other as $key => $tbl) {
                    $this->matchval($key,$tbl,$db);
            }
        }


        $query = $db->get();

        return $query->getResult();
    }
    public function footermenujoin($id)
    {

        $db = $this->dbs->table("tbl_footermenus a");
        $db->join("tbl_footerlink b", "a.title_footlink_id=b.id");
       // $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
            $db->select("a.title_footlink_id,a.status,a.head,a.id,b.title,b.status,a.url,a.content");
       $db->where('a.id', $id);
            $result = $db->get();
           
            return $result->getResult();
    }

    public function footermenujoinhome($id)
    {

        $db = $this->dbs->table("tbl_footermenus a");
        $db->join("tbl_footerlink b", "a.title_footlink_id=b.id");
       // $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
            $db->select("a.title_footlink_id,a.status,a.head,a.id,b.title,b.status");
       //$db->where('b.id', $id);
            $result = $db->get();
           
            return $result->getResult();
    }



    public function footerlinkjoin()
    {

        $db = $this->dbs->table("tbl_footermenus a");
      $db->join("tbl_footerlink b", "a.title_footlink_id=b.id");
        $db->select("a.title_footlink_id,a.head,a.id,b.status,b.title,b.status,");
    // $db->where('a.id', $id);
        $result = $db->get();
       
        return $result->getResult();
    }

    public function mobicon($id)
    {

        $db = $this->dbs->table("tbl_iconmodule_item a");
      $db->join("tbl_testi b", "a.id_title_testi=b.id");
        $db->select("a.id_title_testi,a.image,b.img,b.title,b.content,");
     $db->where('a.id_title_testi', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function mobicon2($id)
    {

        $db = $this->dbs->table("tbl_iconmodule_item a");
      $db->join("tbl_testi b", "a.id_title_testi=b.id");
        $db->select("a.id_title_testi,a.image,b.img,b.title,b.content,");
     $db->where('a.id_title_testi', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    
    public function mobicon3($id)
    {

        $db = $this->dbs->table("tbl_iconmodule_item a");
      $db->join("tbl_testi b", "a.id_title_testi=b.id");
        $db->select("a.id_title_testi,a.image,b.img,b.title,b.content,");
     $db->where('a.id_title_testi', $id);
        $result = $db->get();
       
        return $result->getResult();
    }


    public function mobicon4($id)
    {

        $db = $this->dbs->table("tbl_iconmodule_item a");
      $db->join("tbl_testi b", "a.id_title_testi=b.id");
        $db->select("a.id_title_testi,a.image,b.img,b.title,b.content,");
     $db->where('a.id_title_testi', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    

    public function menudiv()
    {

        $db = $this->dbs->table("tbl_submenu a");
    $db->join("tbl_mainmenu b", "a.main=b.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.active,b.head,b.status");
    // $db->where('a.id', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function submenudiv()
    {

        $db = $this->dbs->table("tbl_submenu a");
    $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.active,a.id,b.title,c.head");
   // $db->where('a.id', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function submenudivion($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  $db->join("tbl_mainmenu b", "a.main=b.id");
   $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.active,a.id,b.title,c.head");
    // $db->where('a.id', $id);
        $result = $db->get();
       
        return $result->getResult();
    }

    public function menudivisionhome($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
     $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function menudivisionhome1($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
  $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }

    public function menudivisionhome2($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
     $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function menudivisionhome3($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
   $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }





    public function menudivisionhome4($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
     $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }
    public function menudivisionhome5($id)
    {

        $db = $this->dbs->table("tbl_submenu a");
  //  $db->join("tbl_mainmenu b", "a.main=b.id");
    $db->join("tbl_subdivmenu c", "a.sub_division=c.id");
        $db->select("a.sub,a.order_no,a.sub_division,a.main,a.content,a.id,a.active,c.head,");
     $db->where('a.sub_division', $id);
        $result = $db->get();
       
        return $result->getResult();
    }

   
    
    
    
    
   



    function time_elapsed_string($datetime, $now = "", $full = true)
    {
        if (!empty($now))
            $now = new \DateTime($now);
        else
            $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }


        if ($full) {
            $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        } else {
            return $string ? implode(', ', $string) : 'just now';
        }
    }

function icons(){
    
$icons = "fa-500px
fa-address-book
fa-address-book-o
fa-address-card
fa-address-card-o
fa-adjust
fa-adn
fa-align-center
fa-align-justify
fa-align-left
fa-align-right
fa-amazon
fa-ambulance
fa-american-sign-language-interpreting
fa-anchor
fa-android
fa-angellist
fa-angle-double-down
fa-angle-double-left
fa-angle-double-right
fa-angle-double-up
fa-angle-down
fa-angle-left
fa-angle-right
fa-angle-up
fa-apple
fa-archive
fa-area-chart
fa-arrow-circle-down
fa-arrow-circle-left
fa-arrow-circle-o-down
fa-arrow-circle-o-left
fa-arrow-circle-o-right
fa-arrow-circle-o-up
fa-arrow-circle-right
fa-arrow-circle-up
fa-arrow-down
fa-arrow-left
fa-arrow-right
fa-arrow-up
fa-arrows
fa-arrows-alt
fa-arrows-h
fa-arrows-v
fa-asl-interpreting
fa-assistive-listening-systems
fa-asterisk
fa-at
fa-audio-description
fa-automobile
fa-backward
fa-balance-scale
fa-ban
fa-bandcamp
fa-bank
fa-bar-chart
fa-bar-chart-o
fa-barcode
fa-bars
fa-bath
fa-bathtub
fa-battery
fa-battery-0
fa-battery-1
fa-battery-2
fa-battery-3
fa-battery-4
fa-battery-empty
fa-battery-full
fa-battery-half
fa-battery-quarter
fa-battery-three-quarters
fa-bed
fa-beer
fa-behance
fa-behance-square
fa-bell
fa-bell-o
fa-bell-slash
fa-bell-slash-o
fa-bicycle
fa-binoculars
fa-birthday-cake
fa-bitbucket
fa-bitbucket-square
fa-bitcoin
fa-black-tie
fa-blind
fa-bluetooth
fa-bluetooth-b
fa-bold
fa-bolt
fa-bomb
fa-book
fa-bookmark
fa-bookmark-o
fa-braille
fa-briefcase
fa-btc
fa-bug
fa-building
fa-building-o
fa-bullhorn
fa-bullseye
fa-bus
fa-buysellads
fa-cab
fa-calculator
fa-calendar
fa-calendar-check-o
fa-calendar-minus-o
fa-calendar-o
fa-calendar-plus-o
fa-calendar-times-o
fa-camera
fa-camera-retro
fa-car
fa-caret-down
fa-caret-left
fa-caret-right
fa-caret-square-o-down
fa-caret-square-o-left
fa-caret-square-o-right
fa-caret-square-o-up
fa-caret-up
fa-cart-arrow-down
fa-cart-plus
fa-cc
fa-cc-amex
fa-cc-diners-club
fa-cc-discover
fa-cc-jcb
fa-cc-mastercard
fa-cc-paypal
fa-cc-stripe
fa-cc-visa
fa-certificate
fa-chain
fa-chain-broken
fa-check
fa-check-circle
fa-check-circle-o
fa-check-square
fa-check-square-o
fa-chevron-circle-down
fa-chevron-circle-left
fa-chevron-circle-right
fa-chevron-circle-up
fa-chevron-down
fa-chevron-left
fa-chevron-right
fa-chevron-up
fa-child
fa-chrome
fa-circle
fa-circle-o
fa-circle-o-notch
fa-circle-thin
fa-clipboard
fa-clock-o
fa-clone
fa-close
fa-cloud
fa-cloud-download
fa-cloud-upload
fa-cny
fa-code
fa-code-fork
fa-codepen
fa-codiepie
fa-coffee
fa-cog
fa-cogs
fa-columns
fa-comment
fa-comment-o
fa-commenting
fa-commenting-o
fa-comments
fa-comments-o
fa-compass
fa-compress
fa-connectdevelop
fa-contao
fa-copy
fa-copyright
fa-creative-commons
fa-credit-card
fa-credit-card-alt
fa-crop
fa-crosshairs
fa-css3
fa-cube
fa-cubes
fa-cut
fa-cutlery
fa-dashboard
fa-dashcube
fa-database
fa-deaf
fa-deafness
fa-dedent
fa-delicious
fa-desktop
fa-deviantart
fa-diamond
fa-digg
fa-dollar
fa-dot-circle-o
fa-download
fa-dribbble
fa-drivers-license
fa-drivers-license-o
fa-dropbox
fa-drupal
fa-edge
fa-edit
fa-eercast
fa-eject
fa-ellipsis-h
fa-ellipsis-v
fa-empire
fa-envelope
fa-envelope-o
fa-envelope-open
fa-envelope-open-o
fa-envelope-square
fa-envira
fa-eraser
fa-etsy
fa-eur
fa-euro
fa-exchange
fa-exclamation
fa-exclamation-circle
fa-exclamation-triangle
fa-expand
fa-expeditedssl
fa-external-link
fa-external-link-square
fa-eye
fa-eye-slash
fa-eyedropper
fa-fa
fa-facebook
fa-facebook-f
fa-facebook-official
fa-facebook-square
fa-fast-backward
fa-fast-forward
fa-fax
fa-feed
fa-female
fa-fighter-jet
fa-file
fa-file-archive-o
fa-file-audio-o
fa-file-code-o
fa-file-excel-o
fa-file-image-o
fa-file-movie-o
fa-file-o
fa-file-pdf-o
fa-file-photo-o
fa-file-picture-o
fa-file-powerpoint-o
fa-file-sound-o
fa-file-text
fa-file-text-o
fa-file-video-o
fa-file-word-o
fa-file-zip-o
fa-files-o
fa-film
fa-filter
fa-fire
fa-fire-extinguisher
fa-firefox
fa-first-order
fa-flag
fa-flag-checkered
fa-flag-o
fa-flash
fa-flask
fa-flickr
fa-floppy-o
fa-folder
fa-folder-o
fa-folder-open
fa-folder-open-o
fa-font
fa-font-awesome
fa-fonticons
fa-fort-awesome
fa-forumbee
fa-forward
fa-foursquare
fa-free-code-camp
fa-frown-o
fa-futbol-o
fa-gamepad
fa-gavel
fa-gbp
fa-ge
fa-gear
fa-gears
fa-genderless
fa-get-pocket
fa-gg
fa-gg-circle
fa-gift
fa-git
fa-git-square
fa-github
fa-github-alt
fa-github-square
fa-gitlab
fa-gittip
fa-glass
fa-glide
fa-glide-g
fa-globe
fa-google
fa-google-plus
fa-google-plus-circle
fa-google-plus-official
fa-google-plus-square
fa-google-wallet
fa-graduation-cap
fa-gratipay
fa-grav
fa-group
fa-h-square
fa-hacker-news
fa-hand-grab-o
fa-hand-lizard-o
fa-hand-o-down
fa-hand-o-left
fa-hand-o-right
fa-hand-o-up
fa-hand-paper-o
fa-hand-peace-o
fa-hand-pointer-o
fa-hand-rock-o
fa-hand-scissors-o
fa-hand-spock-o
fa-hand-stop-o
fa-handshake-o
fa-hard-of-hearing
fa-hashtag
fa-hdd-o
fa-header
fa-headphones
fa-heart
fa-heart-o
fa-heartbeat
fa-history
fa-home
fa-hospital-o
fa-hotel
fa-hourglass
fa-hourglass-1
fa-hourglass-2
fa-hourglass-3
fa-hourglass-end
fa-hourglass-half
fa-hourglass-o
fa-hourglass-start
fa-houzz
fa-html5
fa-i-cursor
fa-id-badge
fa-id-card
fa-id-card-o
fa-ils
fa-image
fa-imdb
fa-inbox
fa-indent
fa-industry
fa-info
fa-info-circle
fa-inr
fa-instagram
fa-institution
fa-internet-explorer
fa-intersex
fa-ioxhost
fa-italic
fa-joomla
fa-jpy
fa-jsfiddle
fa-key
fa-keyboard-o
fa-krw
fa-language
fa-laptop
fa-lastfm
fa-lastfm-square
fa-leaf
fa-leanpub
fa-legal
fa-lemon-o
fa-level-down
fa-level-up
fa-life-bouy
fa-life-buoy
fa-life-ring
fa-life-saver
fa-lightbulb-o
fa-line-chart
fa-link
fa-linkedin
fa-linkedin-square
fa-linode
fa-linux
fa-list
fa-list-alt
fa-list-ol
fa-list-ul
fa-location-arrow
fa-lock
fa-long-arrow-down
fa-long-arrow-left
fa-long-arrow-right
fa-long-arrow-up
fa-low-vision
fa-magic
fa-magnet
fa-mail-forward
fa-mail-reply
fa-mail-reply-all
fa-male
fa-map
fa-map-marker
fa-map-o
fa-map-pin
fa-map-signs
fa-mars
fa-mars-double
fa-mars-stroke
fa-mars-stroke-h
fa-mars-stroke-v
fa-maxcdn
fa-meanpath
fa-medium
fa-medkit
fa-meetup
fa-meh-o
fa-mercury
fa-microchip
fa-microphone
fa-microphone-slash
fa-minus
fa-minus-circle
fa-minus-square
fa-minus-square-o
fa-mixcloud
fa-mobile
fa-mobile-phone
fa-modx
fa-money
fa-moon-o
fa-mortar-board
fa-motorcycle
fa-mouse-pointer
fa-music
fa-navicon
fa-neuter
fa-newspaper-o
fa-object-group
fa-object-ungroup
fa-odnoklassniki
fa-odnoklassniki-square
fa-opencart
fa-openid
fa-opera
fa-optin-monster
fa-outdent
fa-pagelines
fa-paint-brush
fa-paper-plane
fa-paper-plane-o
fa-paperclip
fa-paragraph
fa-paste
fa-pause
fa-pause-circle
fa-pause-circle-o
fa-paw
fa-paypal
fa-pencil
fa-pencil-square
fa-pencil-square-o
fa-percent
fa-phone
fa-phone-square
fa-photo
fa-picture-o
fa-pie-chart
fa-pied-piper
fa-pied-piper-alt
fa-pied-piper-pp
fa-pinterest
fa-pinterest-p
fa-pinterest-square
fa-plane
fa-play
fa-play-circle
fa-play-circle-o
fa-plug
fa-plus
fa-plus-circle
fa-plus-square
fa-plus-square-o
fa-podcast
fa-power-off
fa-print
fa-product-hunt
fa-puzzle-piece
fa-qq
fa-qrcode
fa-question
fa-question-circle
fa-question-circle-o
fa-quora
fa-quote-left
fa-quote-right
fa-ra
fa-random
fa-ravelry
fa-rebel
fa-recycle
fa-reddit
fa-reddit-alien
fa-reddit-square
fa-refresh
fa-registered
fa-remove
fa-renren
fa-reorder
fa-repeat
fa-reply
fa-reply-all
fa-resistance
fa-retweet
fa-rmb
fa-road
fa-rocket
fa-rotate-left
fa-rotate-right
fa-rouble
fa-rss
fa-rss-square
fa-rub
fa-ruble
fa-rupee
fa-s15
fa-safari
fa-save
fa-scissors
fa-scribd
fa-search
fa-search-minus
fa-search-plus
fa-sellsy
fa-send
fa-send-o
fa-server
fa-share
fa-share-alt
fa-share-alt-square
fa-share-square
fa-share-square-o
fa-shekel
fa-sheqel
fa-shield
fa-ship
fa-shirtsinbulk
fa-shopping-bag
fa-shopping-basket
fa-shopping-cart
fa-shower
fa-sign-in
fa-sign-language
fa-sign-out
fa-signal
fa-signing
fa-simplybuilt
fa-sitemap
fa-skyatlas
fa-skype
fa-slack
fa-sliders
fa-slideshare
fa-smile-o
fa-snapchat
fa-snapchat-ghost
fa-snapchat-square
fa-snowflake-o
fa-soccer-ball-o
fa-sort
fa-sort-alpha-asc
fa-sort-alpha-desc
fa-sort-amount-asc
fa-sort-amount-desc
fa-sort-asc
fa-sort-desc
fa-sort-down
fa-sort-numeric-asc
fa-sort-numeric-desc
fa-sort-up
fa-soundcloud
fa-space-shuttle
fa-spinner
fa-spoon
fa-spotify
fa-square
fa-square-o
fa-stack-exchange
fa-stack-overflow
fa-star
fa-star-half
fa-star-half-empty
fa-star-half-full
fa-star-half-o
fa-star-o
fa-steam
fa-steam-square
fa-step-backward
fa-step-forward
fa-stethoscope
fa-sticky-note
fa-sticky-note-o
fa-stop
fa-stop-circle
fa-stop-circle-o
fa-street-view
fa-strikethrough
fa-stumbleupon
fa-stumbleupon-circle
fa-subscript
fa-subway
fa-suitcase
fa-sun-o
fa-superpowers
fa-superscript
fa-support
fa-table
fa-tablet
fa-tachometer
fa-tag
fa-tags
fa-tasks
fa-taxi
fa-telegram
fa-television
fa-tencent-weibo
fa-terminal
fa-text-height
fa-text-width
fa-th
fa-th-large
fa-th-list
fa-themeisle
fa-thermometer
fa-thermometer-0
fa-thermometer-1
fa-thermometer-2
fa-thermometer-3
fa-thermometer-4
fa-thermometer-empty
fa-thermometer-full
fa-thermometer-half
fa-thermometer-quarter
fa-thermometer-three-quarters
fa-thumb-tack
fa-thumbs-down
fa-thumbs-o-down
fa-thumbs-o-up
fa-thumbs-up
fa-ticket
fa-times
fa-times-circle
fa-times-circle-o
fa-times-rectangle
fa-times-rectangle-o
fa-tint
fa-toggle-down
fa-toggle-left
fa-toggle-off
fa-toggle-on
fa-toggle-right
fa-toggle-up
fa-trademark
fa-train
fa-transgender
fa-transgender-alt
fa-trash
fa-trash-o
fa-tree
fa-trello
fa-tripadvisor
fa-trophy
fa-truck
fa-try
fa-tty
fa-tumblr
fa-tumblr-square
fa-turkish-lira
fa-tv
fa-twitch
fa-twitter
fa-twitter-square
fa-umbrella
fa-underline
fa-undo
fa-universal-access
fa-university
fa-unlink
fa-unlock
fa-unlock-alt
fa-unsorted
fa-upload
fa-usb
fa-usd
fa-user
fa-user-circle
fa-user-circle-o
fa-user-md
fa-user-o
fa-user-plus
fa-user-secret
fa-user-times
fa-users
fa-vcard
fa-vcard-o
fa-venus
fa-venus-double
fa-venus-mars
fa-viacoin
fa-viadeo
fa-viadeo-square
fa-video-camera
fa-vimeo
fa-vimeo-square
fa-vine
fa-vk
fa-volume-control-phone
fa-volume-down
fa-volume-off
fa-volume-up
fa-warning
fa-wechat
fa-weibo
fa-weixin
fa-whatsapp
fa-wheelchair
fa-wheelchair-alt
fa-wifi
fa-wikipedia-w
fa-window-close
fa-window-close-o
fa-window-maximize
fa-window-minimize
fa-window-restore
fa-windows
fa-won
fa-wordpress
fa-wpbeginner
fa-wpexplorer
fa-wpforms
fa-wrench
fa-xing
fa-xing-square
fa-y-combinator
fa-y-combinator-square
fa-yahoo
fa-yc
fa-yc-square
fa-yelp
fa-yen
fa-yoast
fa-youtube
fa-youtube-play
fa-youtube-square";
return explode("fa-",$icons);

}

}