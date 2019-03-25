<?php

    // Function for managing lists of "thing" types in the admin.
    // Called per admin screen as required.

    function do_thingtypes($thingtype_id, $thingtype_nicename, $thingtype_message){
        // Grab the existing thingtypes
        $thingtypes = json_decode(get_option($thingtype_id), TRUE);
        if(!is_array($thingtypes)){
            $thingtypes = array(); //Initialise UserTypes if the option is not yet set
        }

        // Add a new one if we have one
        if(isset($_POST['thingtype_label'])){
            $new_thingtype = array('label' => $_POST['thingtype_label'], 'id' => str_replace(" ", "_", $_POST['thingtype_id']));
            // Add new thing type
            // Make sure we're not duping...
            if(!in_array_r($new_thingtype['id'], $thingtypes)){
                $thingtypes[] = $new_thingtype;
                update_option($thingtype_id, json_encode($thingtypes));
            } else {
                echo '<div class="alert">'.$thingtype_nicename.' type id <strong>'.$new_thingtype['id'].'</strong> already exists!</div>';
            }
        }

        // Delete one if needs be
        if(isset($_POST['thingtype_del'])){
            $thingtype_del = $_POST['thingtype_del'];
            
            $thingtypes = array_values(unset_r($thingtypes, 'id', $thingtype_del));
            update_option($thingtype_id, json_encode($thingtypes));

            /*$usertypes = array_values(array_diff($usertypes,array($usertype_del)));
            update_option('usertypes', json_encode($usertypes));*/
            echo '<div class="alert">'.$thingtype_nicename.' type id <strong>'.$thingtype_del.'</strong> deleted!</div>';
        }

?>
        <div class="wrap">
            <h2>Portfolio Options</h2>
            <h3><?php echo $thingtype_nicename; ?> Types</h3>
            <p><?php echo $thingtype_message; ?></p>
            <h4>Current <?php echo $thingtype_nicename; ?> Types</h4>
            <ul>
            <?php
                    foreach($thingtypes as $thingtype){
                        echo '<li><form method="post" action="#"><input type="hidden" name="thingtype_del" value="'.$thingtype['id'].'" /><input type="submit" name="submit" value="X" /> '.$thingtype['label'].' ('.$thingtype['id'].')</form></li>';
                    }
            ?>
            </ul>
            <form method="post" action="#">
                <p><strong>New <?php echo $thingtype_nicename; ?> Type</strong></p>
                <p>Label: <input type="text" name="thingtype_label" /></p>
                <p>ID: <input type="text" name="thingtype_id" /></p>
                <p><input type="submit" name="submit" value="Add ".$thingtype_nicename." Type" /></p>
            </form>
        </div>

<?php
    }

?>