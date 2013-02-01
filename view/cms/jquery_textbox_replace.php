<script type="text/javascript">
    /* remplace les champs input par les combobox grace au plugin textbox */
    jQuery("input[type=text].textbox_keys").textbox({
        items    : <?php echo $data['params_keys']; ?>
    });
    jQuery("input[type=text].textbox_vals").textbox({
        items    : <?php echo $data['params_vals']; ?>
    });
</script>
