<?php
/**
 * Some code in this file is taken from Comment Attachment plugin released @GPL II.
 * CREDITS: Martin Pícha (http://latorante.name)
 */

if (!defined('ABSPATH')) { exit; }

if (!class_exists('WPLMS_Assignments')){
    class WPLMS_Assignments
    {
        private $adminCheckboxes;
        private $adminPrefix    = 'assignmentAttachment';
        private $key            = 'attachment';
        private $settings;


        public function __construct(){ 
            $this->settings = $this->getSavedSettings();
            $this->defineConstants();
            add_action('plugins_loaded', array($this, 'loaded'));
            add_action('init', array($this, 'init'));
            add_action('admin_init', array($this, 'adminInit'));
            add_action('wplms_student_course_reset',array($this,'reset_assignments'),10,2);
            add_action('wplms_before_single_assignment',array($this,'wplms_assignments_before_single_assignment'));
            //add_action('wplms_assignment_after_content',array($this,'assignment_result'));
        }


        function reset_assignments($course_id,$user_id){
            global $wpdb;
            //get all assignments connected to the course_id
            $results = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s and meta_value LIKE %s","vibe_assignment_course","%$course_id%"),ARRAY_A);
            if(is_array($results) && count($results)){
                foreach($results as $result){
                    delete_user_meta($user_id,$result['post_id']);
                    $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$result['post_id'],$user_id));
                }    
            }
        }

        function wplms_assignments_before_single_assignment(){
           if(!is_user_logged_in())
             return;

             global $post;
             $user_id = get_current_user_id();
             if(wplms_assignment_answer_posted()){
               $submitted = get_post_meta($post->ID,$user_id,true);
               if(empty($submitted)){
                   if(update_post_meta($post->ID,$user_id,0)){
                       do_action('wplms_submit_assignment',$post->ID,$user_id);
                      return;
                   }
               }
             }
        }
        /******************* Inits, innit :D *******************/

        /**
         * Loaded, check request
         */

        public function loaded()
        {
            // check to delete att
            if(isset($_GET['deleteAtt']) && ($_GET['deleteAtt'] == '1')){
                if((isset($_GET['c'])) && is_numeric($_GET['c'])){
                    WPLMS_Assignments::deleteAttachment($_GET['c']);
                    delete_comment_meta($_GET['c'], 'attachmentId');
                    add_action('admin_notices',array($this, 'mynotice'));
                }
            }
        }

       function mynotice(){
            echo "<div class='updated'><p>".__('Assignment Attachment deleted.','wplms-assignments')."</p></div>";
        }
        /**
         * Classic init
         */

        public function init(){
        
            if(!$this->checkRequirements()){ return; }
            add_filter('preprocess_comment',        array($this, 'checkAttachment'));
            add_action('comment_form_top',          array($this, 'displayBeforeForm'));
            add_action('comment_form_before_fields',array($this, 'displayFormAttBefore'));
            add_action('comment_form_logged_in_after',array($this, 'displayFormAtt'));
            add_filter('comment_text',              array($this, 'displayAttachment'));
            add_action('comment_post',              array($this, 'saveAttachment'));
            add_action('delete_comment',            array($this, 'deleteAttachment'));
            add_filter('upload_mimes',              array($this, 'getAllowedUploadMimes'));
            add_filter('comment_notification_text', array($this, 'notificationText'), 10, 2);
        }


        /**
         * Admin init
         */

        public function adminInit()
        {
            $this->setUserNag();
            add_filter('comment_row_actions', array($this, 'addCommentActionLinks'), 10, 2);
        }


        /*************** Plugins admin settings ****************/

        /**
         * Get's admin settings page variables
         *
         * @return mixed
         */

        public function getSettings() {
            $this->settings = $this->getAllowedFileExtensions();
        }


        private function getSavedSettings(){ 
            $this->settings = $this->getAllowedFileExtensions();
        }


        /**
         * Define plugin constatns
         */

        private function defineConstants(){
            
            if(!defined(ATT_REQ))
                define('ATT_REQ',   TRUE );

            define('ATT_BIND',  TRUE );
            define('ATT_DEL',   TRUE );
            define('ATT_LINK',  TRUE );
            define('ATT_THUMB',  TRUE );
            define('ATT_PLAY',  TRUE );
            define('ATT_POS',   'before' );
            define('ATT_APOS',  'before');
            define('ATT_TITLE', __('Upload Assignment','wplms-assignments'));
            if ( ! defined( 'ATT_MAX' ) )
                define('ATT_MAX',  $this->getmaxium_upload_file_size());    
        }


        /**
         * For image thumb dropdown.
         *
         * @return mixed
         */

        private function getRegisteredImageSizes()
        {
            foreach(get_intermediate_image_sizes() as $size){
                $arr[$size] = ucfirst($size);
            };
            return $arr;
        }

        function getmaxium_upload_file_size(){
            global $post;
            $max_upload = (int)(ini_get('upload_max_filesize'));
            $max_post = (int)(ini_get('post_max_size'));
            $memory_limit = (int)(ini_get('memory_limit'));
            $upload_mb = min($max_upload, $max_post, $memory_limit);

            if(isset($post) && is_object($post) && isset($post->ID))
            $attachment_size=get_post_meta($post->ID,'vibe_attachment_size',true);

            if(isset($attachment_size) && is_numeric($attachment_size)){
                if($attachment_size < $upload_mb)
                $upload_mb=$attachment_size;
            }

            return $upload_mb;
        }
        /**
         * If there's a place to set up those mime types,
         * it's here.
         *
         * @return array
         */

        private function getMimeTypes()
        {
            return array(
                'JPG' => array(
                                'image/jpeg',
                                'image/jpg',
                                'image/jp_',
                                'application/jpg',
                                'application/x-jpg',
                                'image/pjpeg',
                                'image/pipeg',
                                'image/vnd.swiftview-jpeg',
                                'image/x-xbitmap'),
                'GIF' => array(
                                'image/gif',
                                'image/x-xbitmap',
                                'image/gi_'),
                'PNG' => array(
                                'image/png',
                                'application/png',
                                'application/x-png'),
                'DOCX'=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'RAR'=> 'application/x-rar',
                'ZIP' => array(
                                'application/zip',
                                'application/x-zip',
                                'application/x-zip-compressed',
                                'application/x-compress',
                                'application/x-compressed',
                                'multipart/x-zip'),
                'DOC' => array(
                                'application/msword',
                                'application/doc',
                                'application/text',
                                'application/vnd.msword',
                                'application/vnd.ms-word',
                                'application/winword',
                                'application/word',
                                'application/x-msw6',
                                'application/x-msword'),
                'PDF' => array(
                                'application/pdf',
                                'application/x-pdf',
                                'application/acrobat',
                                'applications/vnd.pdf',
                                'text/pdf',
                                'text/x-pdf'),
                'PPT' => array(
                                'application/vnd.ms-powerpoint',
                                'application/mspowerpoint',
                                'application/ms-powerpoint',
                                'application/mspowerpnt',
                                'application/vnd-mspowerpoint',
                                'application/powerpoint',
                                'application/x-powerpoint',
                                'application/x-m'),
                'PPTX'=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'PPS' => 'application/vnd.ms-powerpoint',
                'PPSX'=> 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
                'ODT' => array(
                                'application/vnd.oasis.opendocument.text',
                                'application/x-vnd.oasis.opendocument.text'),
                'XLS' => array(
                                'application/vnd.ms-excel',
                                'application/msexcel',
                                'application/x-msexcel',
                                'application/x-ms-excel',
                                'application/vnd.ms-excel',
                                'application/x-excel',
                                'application/x-dos_ms_excel',
                                'application/xls'),
                'XLSX'=> 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'MP3' => array(
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'audio/mp3',
                                'audio/x-mp3',
                                'audio/mpeg3',
                                'audio/x-mpeg3',
                                'audio/mpg',
                                'audio/x-mpg',
                                'audio/x-mpegaudio'),
                'M4A' => 'audio/mp4a-latm',
                'OGG' => array(
                                'audio/ogg',
                                'application/ogg'),
                'WAV' => array(
                                'audio/wav',
                                'audio/x-wav',
                                'audio/wave',
                                'audio/x-pn-wav'),
                'WMA' => 'audio/x-ms-wma',
                'MP4' => array(
                                'video/mp4v-es',
                                'audio/mp4'),
                'M4V' => array(
                                'video/mp4',
                                'video/x-m4v'),
                'MOV' => array(
                                'video/quicktime',
                                'video/x-quicktime',
                                'image/mov',
                                'audio/aiff',
                                'audio/x-midi',
                                'audio/x-wav',
                                'video/avi'),
                'WMV' => 'video/x-ms-wmv',
                'AVI' => array(
                                'video/avi',
                                'video/msvideo',
                                'video/x-msvideo',
                                'image/avi',
                                'video/xmpg2',
                                'application/x-troff-msvideo',
                                'audio/aiff',
                                'audio/avi'),
                'MPG' => array(
                                'video/avi',
                                'video/mpeg',
                                'video/mpg',
                                'video/x-mpg',
                                'video/mpeg2',
                                'application/x-pn-mpg',
                                'video/x-mpeg',
                                'video/x-mpeg2a',
                                'audio/mpeg',
                                'audio/x-mpeg',
                                'image/mpg'),
                'OGV' => 'video/ogg',
                '3GP' => array(
                                'audio/3gpp',
                                'video/3gpp'),
                '3G2' => array(
                                'video/3gpp2',
                                'audio/3gpp2'),
                                'FLV' => 'video/x-flv',
                                'WEBM'=> 'video/webm',
                                'APK' => 'application/vnd.android.package-archive',
            );
        }


        /**
         * Gets allowed file types extensions
         *
         * @return array
         */

        public function getAllowedFileExtensions()
        {
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            global $post;
            if(!function_exists('vibe_sanitize'))
                return;
            $attachment_type=vibe_sanitize(get_post_meta($post->ID,'vibe_attachment_type',false));
            if(in_array('JPG',$attachment_type)){
                $attachment_type[]='JPEG';
            }
            return $attachment_type;
        }


        /**
         * Gets allowed file types for attachment check.
         *
         * @return array
         */

        public function getAllowedMimeTypes()
        {
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            $ext=$this->getAllowedFileExtensions();
            
            foreach($ext as $key){
                if(array_key_exists($key, $pluginFileTypes)){
                    if(!function_exists('finfo_file') || !function_exists('mime_content_type')){
                        if(($key ==  'DOCX') || ($key == 'DOC') || ($key == 'PDF') ||
                            ($key == 'ZIP') || ($key == 'RAR')){
                            $return[] = 'application/octet-stream';
                        }
                    }
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $return[] = $fileType;
                        }
                    } else {
                        $return[] = $pluginFileTypes[$key];
                    }
                }
            }
            return $return;
        }

        function _mime_content_type($filename) {

            /**
            *    mimetype
            *    Returns a file mimetype. Note that it is a true mimetype fetch, using php and OS methods. It will NOT
            *    revert to a guessed mimetype based on the file extension if it can't find the type.
            *    In that case, it will return false
            **/
            if (!file_exists($filename) || !is_readable($filename)) return false;
            if(class_exists('finfo')){
                $result = new finfo();
                if (is_resource($result) === true) {
                    return $result->file($filename, FILEINFO_MIME_TYPE);
                }
            }
            
             // Trying finfo
             if (function_exists('finfo_open')) {
               $finfo = finfo_open(FILEINFO_MIME);
               $mimeType = finfo_file($finfo, $filename);
               finfo_close($finfo);
               // Mimetype can come in text/plain; charset=us-ascii form
               if (strpos($mimeType, ';')) list($mimeType,) = explode(';', $mimeType);
               return $mimeType;
             }
            
             // Trying mime_content_type
             if (function_exists('mime_content_type')) {
               return mime_content_type($filename);
             }
            

             // Trying to get mimetype from images
             $imageData = getimagesize($filename);
             if (!empty($imageData['mime'])) {
               return $imageData['mime'];
             }
             // Trying exec
             if (function_exists('exec')) {
               $mimeType = exec("/usr/bin/file -i -b $filename");
               if(strpos($mimeType,';')){
                 $mimeTypes = explode(';',$mimeType);
                 return $mimeTypes[0];
               }
               if (!empty($mimeType)) return $mimeType;
             }
            return false;
        }

        /**
         * This one actually will need explaining, it's hard
         *
         * @param array $existing
         * @return array
         */

        public function getAllowedUploadMimes($existing = array())
        {
            // we get mime types and saved file types
            $return = array();
            $pluginFileTypes = $this->getMimeTypes();
            if(is_array($this->settings))
            foreach($this->settings as $key ){
                // list thru them and if it's allowed and not in list, we added there,
                // in reality, I'm thinking about removing the wp ones, and all mines,
                // since wordpress mime types are very limited, we can do better guys
                // cuase it sucks, and doesn't have enough mime types, actually let's
                // just do it ...
                if(array_key_exists($key, $pluginFileTypes)){
                    $keyCheck = strtolower($key);
                    // here we would have checked, if mime type is already there,
                    // but we want strong list of mime types, so we just add it all.
                    if(is_array($pluginFileTypes[$key])){
                        foreach($pluginFileTypes[$key] as $fileType){
                            $keyHacked = preg_replace("/[^0-9a-zA-Z ]/", "", $fileType);
                            $return[$keyCheck . '|' . $keyCheck . '_' . $keyHacked] = $fileType;
                        }
                    } else {
                        $return[$keyCheck] = $pluginFileTypes[$key];
                    }
                }
            }
            return array_merge($return, $existing);
        }


        /*
         * For error info, and form upload info.
         */

        public function displayAllowedFileTypes()
        {   
            $fileTypesString = '';
            $filetypes = $this->getAllowedFileExtensions();
            if(isset($filetypes) && is_Array($filetypes))
            foreach($filetypes as $value){
                $fileTypesString .= $value . ', ';
            }

            return substr($fileTypesString, 0, -2);
        }


        /**
         * For attachment display, get's image mime types
         *
         * @return array
         */

        public function getImageMimeTypes()
        {
            return array(
                'image/jpeg',
                'image/jpg',
                'image/jp_',
                'application/jpg',
                'application/x-jpg',
                'image/pjpeg',
                'image/pipeg',
                'image/vnd.swiftview-jpeg',
                'image/x-xbitmap',
                'image/gif',
                'image/x-xbitmap',
                'image/gi_',
                'image/png',
                'application/png',
                'application/x-png'
            );
        }


        /**
         * For attachment display, get's audio mime types
         *
         * @return array
         */
        // TODO: only check ones audio player can play?

        public function getAudioMimeTypes()
        {
            return array(
                'audio/mpeg',
                'audio/x-mpeg',
                'audio/mp3',
                'audio/x-mp3',
                'audio/mpeg3',
                'audio/x-mpeg3',
                'audio/mpg',
                'audio/x-mpg',
                'audio/x-mpegaudio',
                'audio/mp4a-latm',
                'audio/ogg',
                'application/ogg',
                'audio/wav',
                'audio/x-wav',
                'audio/wave',
                'audio/x-pn-wav',
                'audio/x-ms-wma'
            );
        }


        /**
         * For attachment display, get's audio mime types
         *
         * @return array
         */

        public function getVideoMimeTypes()
        {
            return array(
                'video/mp4v-es',
                'audio/mp4',
                'video/mp4',
                'video/x-m4v',
                'video/quicktime',
                'video/x-quicktime',
                'image/mov',
                'audio/aiff',
                'audio/x-midi',
                'audio/x-wav',
                'video/avi',
                'video/x-ms-wmv',
                'video/avi',
                'video/msvideo',
                'video/x-msvideo',
                'image/avi',
                'video/xmpg2',
                'application/x-troff-msvideo',
                'audio/aiff',
                'audio/avi',
                'video/avi',
                'video/mpeg',
                'video/mpg',
                'video/x-mpg',
                'video/mpeg2',
                'application/x-pn-mpg',
                'video/x-mpeg',
                'video/x-mpeg2a',
                'audio/mpeg',
                'audio/x-mpeg',
                'image/mpg',
                'video/ogg',
                'audio/3gpp',
                'video/3gpp',
                'video/3gpp2',
                'audio/3gpp2',
                'video/x-flv',
                'video/webm',
            );
        }


        /**
         * This way we sort of fake our "enctype" in, since there's not ohter hook
         * that would allow us to put it there naturally, and no, we won't use JS for that
         * since that's rubbish and not bullet-proof. Yes, this creates empty form on page,
         * but who cares, it works and does the trick.
         */

        public function displayBeforeForm(){
            if(get_post_type() != WPLMS_ASSIGNMENTS_CPT)
                return;

            echo '</form><form action="'. get_home_url() .'/wp-comments-post.php" method="POST" enctype="multipart/form-data" id="attachmentForm" class="comment-form" novalidate>';
        }


        /*
         * Display form upload field.
         */

        public function displayFormAttBefore()  { 
            if(get_post_type() != WPLMS_ASSIGNMENTS_CPT)
                return; 
            if(ATT_POS == 'before'){ $this->displayFormAtt(); } 
        }
        public function displayFormAtt(){   
            if(get_post_type() != WPLMS_ASSIGNMENTS_CPT)
                return;
            global $post;
            $assignment_submission_type=get_post_meta($post->ID,'vibe_assignment_submission_type',true);
            if(!isset($assignment_submission_type) || $assignment_submission_type != 'upload')
                return;
            
            $user_id = get_current_user_id();
            $answers=get_comments(array(
              'post_id' => $post->ID,
              'status' => 'approve',
              'number'=>1,
              'user_id' => $user_id
              ));
            if(isset($answers) && is_array($answers) && count($answers)){
                $answer = end($answers);
                $content = $answer->comment_content;
            }else{
                $content='';
            }
             

            $required = ATT_REQ ? ' <span class="required">*</span>' : '';
            echo '<p class="comment-form-url comment-form-attachment">'.
                    '<label for="attachment">' . ATT_TITLE . $required .'<small class="attachmentRules">&nbsp;&nbsp;('.__('Allowed file types','wplms-assignments').': <strong>'. $this->displayAllowedFileTypes() .'</strong>, '.__('maximum file size','wplms-assignments').': <strong>'. $this->getmaxium_upload_file_size() .__('MB(s)','wplms-assignments').'.</strong></small></label>'.
                '</p>';

            if(isset($answers) && is_array($answers) && count($answers)){
                    $attachmentIDs = get_comment_meta($answer->comment_ID,'attachmentId',true);
                    echo '<p class="assignment_submission"><strong>'.__('RECENTLY UPLOADED','wplms-assignments').' : </strong>';
                if(is_array($attachmentIDs)){
                    foreach($attachmentIDs as $attachmentID){
                        $attachmentName = basename(get_attached_file($attachmentID));
                        $att=wp_get_attachment_url($attachmentID);
                        echo '<br><a class="attachmentLink" target="_blank" href="'.$att.'">'.$attachmentName.'</a>';
                    }
                }else{
                    $attachmentID = $attachmentIDs;
                    // if comma saperated the foreach
                    $attachmentName = basename(get_attached_file($attachmentID));
                    $att=wp_get_attachment_url($attachmentID);
                    echo '<a class="attachmentLink" target="_blank" href="'.$att.'">'.$attachmentName.'</a>';
                }
                echo '<a id="clear_previous_submissions" data-id="'.get_the_ID().'" data-security="'.wp_create_nonce('user'.$user_id).'"><i class="icon-x"></i></a></p>';
            }

            $extensions = $this->getAllowedFileExtensions();
            $extensions = implode('|',$extensions);
            echo '<p class="comment-form-url comment-form-attachment">
                    <input id="attachment" name="attachment[]" type="file" class="multi" accept="'.$extensions.'"/>
                  </p>';

        }

        /**
         * Rearrange $_Files array
         *
         * @param $data
         * @return mixed
         */
        function reArrayFiles(&$file_post) {

            $file_ary = array();
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);

            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $file_post[$key][$i];
                }
            }

            return $file_ary;
        }
        /**
         * Checks attachment, size, and type and throws error if something goes wrong.
         *
         * @param $data
         * @return mixed
         */

        public function checkAttachment($data){   

            if(get_post_type($data['comment_post_ID']) != WPLMS_ASSIGNMENTS_CPT)
                return $data;

            $assignmenttype = get_post_meta($data['comment_post_ID'],'vibe_assignment_submission_type',true);

            if($assignmenttype != 'upload')
                return $data;

            if(!empty($_FILES)){
                $files = $this->reArrayFiles($_FILES['attachment']);
                foreach($files as $file){
                    if($file['size'] > 0 && $file['error'] == 0){

                        $fileInfo = pathinfo($file['name']);
                        $fileExtension = strtolower($fileInfo['extension']);
                        $fileType = $this->_mime_content_type($file['tmp_name']); // custom function
                        if (!in_array($fileType, $this->getAllowedMimeTypes()) || !in_array(strtoupper($fileExtension), $this->getAllowedFileExtensions()) || $file['size'] > ($this->getmaxium_upload_file_size() * 1048576)) { // file size from admin
                            wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('File you upload must be valid file type','wplms-assignments').' <strong>('. $this->displayAllowedFileTypes() .')</strong>'.__(', and under ','wplms-assignments'). $this->getmaxium_upload_file_size() .__('MB(s)!','wplms-assignments'));
                        }

                    } elseif (ATT_REQ && $file['error'] == 4) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('Please upload an Attachment.','wplms-assignments'));
                    } elseif($file['error'] == 1) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('The uploaded file exceeds the upload_max_filesize directive in php.ini.','wplms-assignments'));
                    } elseif($file['error'] == 2) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.','wplms-assignments'));
                    } elseif($file['error'] == 3) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('The uploaded file was only partially uploaded. Please try again later.','wplms-assignments'));
                    } elseif($file['error'] == 6) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('Missing a temporary folder.','wplms-assignments'));
                    } elseif($file['error'] == 7) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('Failed to write file to disk.','wplms-assignments'));
                    } elseif($file['error'] == 7) {
                        wp_die('<strong>'.__('ERROR:','wplms-assignments').'</strong> '.__('A PHP extension stopped the file upload.','wplms-assignments'));
                    }
                }
            }
            
            return $data;
        }


        /**
         * Notification email message
         *
         * @param $notify_message
         * @param $comment_id
         * @return string
         */

        public function notificationText($notify_message,  $comment_id)
        {
            if(WPLMS_Assignments::hasAttachment($comment_id)){
                $attachmentIds = get_comment_meta($comment_id, 'attachmentId', TRUE);
                if(is_Array($attachmentIds)){
                    foreach($attachmentIds as $attachmentId){
                        $attachmentName = basename(get_attached_file($attachmentId));
                        $notify_message .= __('Attachment:','wplms-assignments') . "\r\n" .  $attachmentName . "\r\n\r\n";
                    }
                }else{
                    $attachmentId = $attachmentIds;
                    $attachmentName = basename(get_attached_file($attachmentId));
                    $notify_message .= __('Attachment:','wplms-assignments') . "\r\n" .  $attachmentName . "\r\n\r\n";
                }
            }
            return $notify_message;
        }


        /**
         * Inserts file attachment from your comment to wordpress
         * media library, assigned to post.
         *
         * @param $fileHandler
         * @param $postId
         * @return mixed
         */

        public function insertAttachment($fileHandler, $postId)
        {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            return media_handle_upload($fileHandler, $postId);
        }


        /**
         * Save attachment to db, with all sizes etc. Assigned
         * to post, or not.
         *
         * @param $commentId
         */

        public function saveAttachment($commentId)
        {
            if(get_post_type($_POST['comment_post_ID']) != WPLMS_ASSIGNMENTS_CPT)    
                return;
            
            $assignmenttype = get_post_meta($_POST['comment_post_ID'],'vibe_assignment_submission_type',true);

            if($assignmenttype != 'upload')
                return;

            $files = $this->reArrayFiles($_FILES['attachment']);
            foreach($files as $file){
                if($file['size'] > 0){
                    $_FILES = array ('attachment' => $file); 
                    $bindId = ATT_BIND ? $_POST['comment_post_ID'] : 0; // TRUE
                    $attachId = $this->insertAttachment('attachment', $bindId);
                    $attachIds[]=$attachId;
                    unset($_FILES);
                }
            }
            $savedassignment=get_comment($commentId);
            update_post_meta($savedassignment->comment_post_ID,$comment_post_ID->user_id,0);
            update_comment_meta($commentId, 'attachmentId', $attachIds);
        }


        /**
         * Displays attachment in comment, according to
         * position selected in settings, and according to way selected in admin.
         *
         * @param $comment
         * @return string
         */

        public function displayAttachment($comment)
        {
            $attachmentIds = get_comment_meta(get_comment_ID(), 'attachmentId', TRUE);
            if(!is_array($attachmentIds)){
                $attachmentIds = array($attachmentIds);
            }
            foreach($attachmentIds as $attachmentId){
                if(is_numeric($attachmentId) && !empty($attachmentId)){

                    // atachement info
                    $attachmentLink = wp_get_attachment_url($attachmentId);
                    $attachmentMeta = wp_get_attachment_metadata($attachmentId);
                    $attachmentName = basename(get_attached_file($attachmentId));
                    $attachmentType = get_post_mime_type($attachmentId);
                    $attachmentRel  = '';

                    // let's do wrapper html
                    $contentBefore  = '<div class="attachmentFile"><p>' . $this->settings[$this->adminPrefix . 'ThumbTitle'] . ' ';
                    $contentAfter   = '</p><div class="clear clearfix"></div></div>';

                    // admin behaves differently
                    if(is_admin()){
                        $contentInner = $attachmentName;
                    } else {
                        // shall we do image thumbnail or not?
                        if(ATT_THUMB && in_array($attachmentType, $this->getImageMimeTypes()) && !is_admin()){
                            $attachmentRel = 'rel="lightbox"';
                            $contentInner = wp_get_attachment_image($attachmentId, ATT_TSIZE);
                            // audio player?
                        } elseif (ATT_PLAY && in_array($attachmentType, $this->getAudioMimeTypes())){
                            if(shortcode_exists('audio')){
                                $contentInner = do_shortcode('[audio src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                            // video player?
                        } elseif (ATT_PLAY && in_array($attachmentType, $this->getVideoMimeTypes())){
                            if(shortcode_exists('video')){
                                $contentInner .= do_shortcode('[video src="'. $attachmentLink .'"]');
                            } else {
                                $contentInner = $attachmentName;
                            }
                            // rest ..
                        } else {
                            $contentInner = '&nbsp;<strong>' . $attachmentName . '</strong>';
                        }
                    }

                    // attachment link, if it's not video / audio
                    if(is_admin()){
                        $contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="'.__('Download','wplms-assignments').': '. $attachmentName .'">';
                            $contentInnerFinal .= $contentInner;
                        $contentInnerFinal .= '</a>';
                    } else {
                        if((ATT_LINK) && !in_array($attachmentType, $this->getAudioMimeTypes()) && !in_array($attachmentType, $this->getVideoMimeTypes())){
                            $contentInnerFinal = '<a '.$attachmentRel.' class="attachmentLink" target="_blank" href="'. $attachmentLink .'" title="Download: '. $attachmentName .'">';
                                $contentInnerFinal .= $contentInner;
                            $contentInnerFinal .= '</a>';
                        } else {
                            $contentInnerFinal = $contentInner;
                        }
                    }

                    // bring a sellotape, this needs taping together
                    $contentInsert = $contentBefore . $contentInnerFinal . $contentAfter;

                    // attachment comment position
                    if(ATT_APOS == 'before' && !is_admin()){
                        $comment = $contentInsert . $comment;
                    } elseif(ATT_APOS == 'after' || is_admin()) {
                        $comment .= $contentInsert;
                    }
                }
            }
            return $comment;
        }


        /**
         * This deletes attachment after comment deletition.
         *
         * @param $commentId
         */

        public function deleteAttachment($commentId)
        {
            $attachmentId = get_comment_meta($commentId, 'attachmentId', TRUE);
            if(is_numeric($attachmentId) && !empty($attachmentId) && ATT_DEL){
                wp_delete_attachment($attachmentId, TRUE);
            }
        }


        /**
         * Has attachment
         *
         * @param $commentId
         * @return bool
         */

        public static function hasAttachment($commentId)
        {
            $attachmentId = get_comment_meta($commentId, 'attachmentId', TRUE);
            if(is_numeric($attachmentId) && !empty($attachmentId)){
                return true;
            }
            return false;
        }


        /*************** Admin Settings Functions **************/

        /**
         * Comment Action links
         *
         * @param $actions
         * @param $comment
         * @return array
         */

        public function addCommentActionLinks($actions, $comment)
        {
            if(WPLMS_Assignments::hasAttachment($comment->comment_ID)){
                $url = $_SERVER["SCRIPT_NAME"] . "?c=$comment->comment_ID&deleteAtt=1";
                $actions['deleteAtt'] = "<a href='$url' title='".esc_attr__('Delete Attachment','wplms-assignments')."'>".__('Delete Attachment','wplms-assignments').'</a>';
            }
            return $actions;
        }


        /***************** Plugin basic weapons ****************/

        /**
         * Let's check Wordpress version, and PHP version and tell those
         * guys whats needed to upgrade, if anything.
         *
         * @return bool
         */

        private function checkRequirements()
        {
            if (!function_exists('mime_content_type') && !function_exists('finfo_file') && version_compare(PHP_VERSION, '5.3.0') < 0){
                add_action('admin_notices', array($this, 'displayFunctionMissingNotice'));
                return TRUE;
            }
            return TRUE;
        }


        /**
         * Notify use about missing needed functions, and less security caused by that, let them hide nag of course.
         */

        public function displayFunctionMissingNotice()
        {
            $currentUser = wp_get_current_user();
            if (!get_user_meta($currentUser->ID, 'AssignmentAttachmentIgnoreNag') && current_user_can('install_plugins')){
                $this->displayAdminError((sprintf(
                    __('Regarding WPLMS Assignments Upload Assignment Functionality : It seems like your PHP installation is missing "mime_content_type" or "finfo_file" functions which are crucial '.
                    'for detecting file types of uploaded attachments. Please update your PHP installation OR be very careful with allowed file types, so '.
                    'intruders won\'t be able to upload dangerous code to your website! | <a href="%1$s">Hide Notice</a>','wplms-assignments'), '?AssignmentAttachmentIgnoreNag=1')), 'updated');
            }
        }


        /**
         * Save user nag if set, if they want to hide the message above.
         */

        private function setUserNag()
        {
            $currentUser = wp_get_current_user();
            if (isset($_GET['AssignmentAttachmentIgnoreNag']) && '1' == $_GET['AssignmentAttachmentIgnoreNag'] && current_user_can('install_plugins')){
                update_user_meta($currentUser->ID, 'AssignmentAttachmentIgnoreNag', 'true', true);
            }
        }


        /**
         * Admin error helper
         *
         * @param $error
         */

        private function displayAdminError($error, $class="error") { echo '<div id="message" class="'. $class .'"><p><strong>' . $error . '</strong></p></div>';  }


        function activate(){
            flush_rewrite_rules(false );
        }

        function deactivate(){
            flush_rewrite_rules(false );
        }

        function assignment_result(){
            if(!is_user_logged_in())
                return;
            $user_id = get_current_user_id();
            global $post;
            $expiry=get_user_meta($user_id,$post->ID,true);
            if($expiry < time()){
                $verify = get_post_meta($post->ID,$user_id,true);
                if(!is_numeric($verify)){
                    update_post_meta($post->ID,$user_id,2);
                }
            }

        }
        protected function __clone(){}

    }
}

