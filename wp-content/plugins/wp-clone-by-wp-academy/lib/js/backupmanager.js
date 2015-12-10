
jQuery(function($) {

    initialize();
    bindActions();
    getSize();
    scanBackupDir();
    uninstall();
    deleteFile();

    function initialize() {
        $("input[name$='backupChoice']").removeAttr('checked');
        checkCreateBackupOption();
    }

    function bindActions() {

        $("input[id='fullBackup']").click(function() {

            $("#RestoreOptions").hide("fast");
            $("#file_directory").hide("fast");
            $("input[name$='createBackup']").attr('checked', true);
            $("input[name$='backupUrl']").removeAttr('checked');
            $("#backupChoices").show("fast");
            $("input#submit").val("Create Backup");

        });

        $("input[id='customBackup']").click(function() {

            $("#RestoreOptions").hide("fast");
            $("#file_directory").show("fast");
            $("input[name$='createBackup']").attr('checked', true);
            $("input[name$='backupUrl']").removeAttr('checked');
            $("#backupChoices").show("fast");
            $("input#submit").val("Create Backup");

        });

        $("input[name$='createBackup']").click(function() {

            $("#RestoreOptions").hide("fast");
            $("input[name$='backupUrl']").attr('checked', false);
            $("input[class$='restoreBackup']").each(function(){ $(this).attr('checked', false) });
            checkCreateBackupOption();

        });

        $("input[class$='restoreBackup']").click(function() {

            $("#RestoreOptions").show("fast");
            $("input[name$='backupUrl']").attr('checked', false);
            $(this).attr('checked', true);
            unCheckCreateBackupOption();
            $("input#submit").val("Restore Backup").removeClass("btn-primary").addClass("btn-warning");

        });

        $("input[name$='backupUrl']").click(function() {
            prepareBackUrlOption();
			$("input#submit").removeClass("btn-primary").addClass("btn-warning");
        });

        $("input[name$='restore_from_url']").focus(function() {
            prepareBackUrlOption();
			$("input#submit").removeClass("btn-primary").addClass("btn-warning");
        });

        $("input#submit").click(function() {

            if ($('#backupUrl').is(':checked')) {

                if ($("input[name$='restore_from_url']").val() == '') {
                    alert('Please enter the url you want to restore from.');
                } else if (!$('#approve').is(':checked')) {
                    alert('Please confirm that you agree to our terms by checking the "I AGREE" checkbox.');
                } else {
                    return getConfirmation('restore');
                }

                return false;

            } else if ($('input[class$="restoreBackup"]').is(':checked')) {

                if ($('#approve').is(':checked')) {
                    return getConfirmation('restore');
                }

                alert('Please confirm that you agree to our terms by checking "I AGREE (Required for "Restore" function):" checkbox.');
                return false;

            } else {
                return getConfirmation('create backup');
            }

            function getConfirmation(toDo) {
                return confirm('This may take a few minutes. Proceed to ' + toDo + ' now?');
            }
        });

        function unCheckCreateBackupOption() {
            $("input[name$='createBackup']").attr('checked', false);
            $("#backupChoices").hide("fast");
        }

        function prepareBackUrlOption() {
            $("#RestoreOptions").show("fast");
            $("input[name$='backupUrl']").attr('checked', true);
            $("input[class$='restoreBackup']").attr('checked', false);
            unCheckCreateBackupOption();
            $("input#submit").val('Restore from URL');
        }

    }

    function checkCreateBackupOption() {
        $("input[name$='createBackup']").attr('checked', true);
        $("#backupChoices").show("fast");
        $("input#submit").val("Create Backup").removeClass("btn-warning").addClass("btn-primary");
        $("input[id='fullBackup']").attr('checked',
            $("input[name$='createBackup']").is(':checked') && !$("input[id$='customBackup']").is(':checked'));
    }

    function getSize() {

        $.ajax({
            url: ajaxurl,
            type: 'get',
            data: {
                'action': 'wpclone-ajax-size',
                'nonce': wpclone.nonce
            },
            success: function(data){
                data = $.parseJSON(data);
                $("span#filesize").html( "There are <code>" + data.files + "</code> files to backup, and their total size is <code>" + data.size + "</code>. Database size is <code>" + data.dbsize + "</code>." );
            },
            error: function(e){
                $("span#filesize").html( "Unable to calculate size." );
            }
        });

    }

    function scanBackupDir() {

        $("a#dirscan").click( function(e){

            e.preventDefault();
            $(this).html("<img src='" + wpclone.spinner + "'>");

            $.ajax({
                url: ajaxurl,
                type: 'get',
                data: {
                    'action': 'wpclone-ajax-dir',
                    'nonce': wpclone.nonce
                },
                success: function(data){
                    window.location.reload(true);
                },
                error: function(e){
                }
            });


        });

    }

    function uninstall() {

        $("a#uninstall").click( function(e){

            e.preventDefault();
            if( ! confirm('This will delete all your backups files, are you sure?') ) return;
            $(this).html("<img src='" + wpclone.spinner + "'>");
            $.ajax({
                url: ajaxurl,
                type: 'get',
                data: {
                    'action': 'wpclone-ajax-uninstall',
                    'nonce': wpclone.nonce
                },
                success: function(data){
                    window.location.reload(true);
                },
                error: function(e){
                }
            });


        });

    }

    function deleteFile() {

        $("table.restore-backup-options a.delete").click( function(e){
            e.preventDefault();
            var row = $(this).closest("tr");
            var cell = $(this).closest("td");
            $(cell).html("<img src='" + wpclone.spinner + "'>");

            $.ajax({
                url: ajaxurl,
                type: 'get',
                data: {
                    'action': 'wpclone-ajax-delete',
                    'fileid': $(this).data("fileid"),
                    'nonce': wpclone.nonce
                },
                success: function(data){
                    data = $.parseJSON( data );

                    if( 'deleted' == data.status ) {
                        $(row).html("<td colspan='5'><strong>" + data.msg + "</strong></td>");
                        $(row).addClass('deleted').hide(700);
                    } else {
                        $(row).html("<td colspan='5'>" + data.msg + "</td>").addClass('delete-error');
                    }
                },
                error: function(e){
                }
            });

        });

    }

});