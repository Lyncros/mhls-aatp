<?
        session_start();
        $PageTitle = "Upload Task Item File";
        $batch_id  = rand(1, 20000000000);
        if (isset($_GET['task_item_id']))
        {
                ?>
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html>
                        <head><title><?= $PageTitle; ?></title>
                                <link rel="stylesheet" type="text/css" href="./style.css"/>
                                <link rel="stylesheet" type="text/css" href="./style.css"/>
                                <link rel="stylesheet" type="text/css" href="./js/jquery/jquery.countdown.css"/>
                                <link rel="stylesheet" type="text/css" href="./include/uploadify/uploadify.css" />
                                <link rel="stylesheet" type="text/css" href="./include/uploadify/upload_form.css" />
                                <!--[if lte IE 7]>
                                <link rel="stylesheet" type="text/css" href="./style.ie.css"/>
                                        <link rel="stylesheet" type="text/css" href="./floatybar.ie.css"/>
                                <![endif]-->
                        </head>
                        <body style="background-color: #374B7Es">

                                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
                                <script type="text/javascript" src="include/uploadify/swfobject.js"></script>
                                <script type="text/javascript" src="include/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
                                <?php
                                require_once("include/connect.php");
                                require_once('security.php');
                                ?>
                                <script>
                                                        
                                       
                                                        
                                                        
                                                        
                                                        
                                      
                                                                                    
                                                                                    
                                        function build_select_list(){
                                                var file_string = "";
                                                var i = 1;
                                                $.each(file_array, function(key, value) { 
                                                        if(i == file_array.length){
                                                                file_string = file_string + value
                                                        }else{
                                                                file_string = file_string + value + ",";
                                                                                            
                                                        }
                                                        i++;
                                                });
                                                $('#file_key_string').val(file_string)
                                        }
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                                                                    
                                        Array.prototype.remove = function(elem) {
                                                var match = -1;

                                                while( (match = this.indexOf(elem)) > -1 ) {
                                                        this.splice(match, 1);
                                                }
                                        };
                                        var locked = false;
                                        var file_array = new Array();
                                                                                    
                                        $(document).ready(function() {
                                                
                                                 $('#test123').click(function(){
                                                
                                                
                                                var temp_id = '';
                                                $('.uploadifyQueueItem').each(function(){
                                                        temp_id = $(this).attr('id').replace('attachment_area_input','');
                                                        $('#attachment_area_input').uploadifySettings('scriptData',{'batch_id': '<?php echo $batch_id; ?>','PHPSESSID': '<?php echo session_id(); ?>', 'FILE_ID': temp_id});
                                                        $('#attachment_area_input').uploadifyUpload(temp_id); 
                                                });
                                                                
                                                               
                                                                
                                        });
                                                
                                                
                                                
                                                
                                                $('#attachment_area_input').uploadify({
                                                        'uploader' : 'include/uploadify/uploadify.swf',
                                                        'script' : 'include/uploadify/uploadify.php',
                                                        'cancelImg' : 'include/uploadify/cancel.png',
                                                        'folder' : 'temp_upload/',
                                                        'scriptData': {batch: <?= "\"" . $batch_id . "\"" ?>,'PHPSESSID': '<?php echo session_id(); ?>'},
                                                        'multi' : true,
                                                        'auto' : false,
                                                        'queueID' : 'custom-queue',
                                                        'simUploadLimit' : 2,
                                                        'buttonImg' : 'gfx/icons/file_choose.jpg',
                                                        'width' : 95,
                                                        'sizeLimit' : 16553600,
                                                        'removeCompleted': false,
                                                        'onSelect'    : function(event,ID,fileObj) {
                                                                locked = true;
                                                                       
                                                                                                        
                                                                                                        
                                                        },
                                                        'onSelectOnce' : function(event,data) {
                                                                                                                                
                                                                               
                                                                                                        
                                                                //$('#attachment_area_input').uploadifyUpload();
                                                        },
                                                        'onComplete'  : function(event, ID, fileObj, response, data) {
                                                                                                            
                                                                                                       
                                                                file_array.push(ID);
                                                                build_select_list();
                                                        },
                                                        'onCancel'    : function(event,ID,fileObj,data) {
                                                                file_array.remove(ID);
                                                                build_select_list();
                                                        },
                                                        'onAllComplete' : function(event,data) {
                                                                $('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
                                                                locked = false;
                                                                                                        
                                                                                                        
                                                                                                        
                                                        }});

                                                $('#submit_btn').click(function(e){
                                                        forminfo =  $('#task_item_upload_form').serialize();
                                                        e.preventDefault();
                                                        if(locked == false){
                                                                $.ajax({
                                                                        type: "POST",
                                                                        url: "process_task_item_upload.php",
                                                                        data: forminfo,
                                                                        dataType: "html",
                                                                        success: function(html) {
                                                                                                            
                                                                        }
                                                                });  
                                                                $('#file_upload_area').html('<h2>Attachments Successfully Added</h2><h3>For large attachments please wait a few minutes for availability.</h3>');
                                                        }else{
                                                                alert("Please Wait for Files To Finish Uploading!");
                                                                                                        
                                                        }
                                                });


                                        });
                                </script>
                                <div id="file_upload_area">
                                        <h2>Add Attachments To Task Item</h2>

                                        <?php
                                        $sql               = "SELECT * FROM `task_items` where ID = " . intval($_GET['task_item_id']);
                                        $current_task_item = mysql_fetch_assoc(mysql_query($sql));
                                        ?>
                                        <h3><?php echo stripslashes($current_task_item['TITLE']) ?></h3>
                                        <form id="task_item_upload_form"><div id="custom-queue"></div><input id="attachment_area_input" />
                                                <?php echo "<input type='hidden' value='{$_GET['task_item_id']}' name='task_item_id' />"; ?>   
                                                <input type="submit" value="Save" id="submit_btn" />
                                                <input type="hidden" value="<?php echo $batch_id; ?>" name="batch_id" />
                                                <input type="hidden" value="" name="file_key_string" id="file_key_string" />
                                        </form>
                                        <a href="#" id="test123">Test</a>
                                </div>
                        </body></html>

                <?php
        }
        else
        {
                echo "Task Item Id Required!";
        }
?>