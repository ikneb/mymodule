<fieldset>
    <div class="panel">
        <div class="panel-heading">
            <legend><i class="icon-info"></i>
                {l s='Comment on product' mod='shortcode'}</legend>
        </div>
        <div class="form-group clearfix">
            <label class="col-lg-3">{l s='ID:' mod='shortcodes'}</label>
            <div class="col-lg-9">{$shortcodes->id}</div>
        </div>
        <div class="form-group clearfix">
            <label class="col-lg-3">{l s='Firstname:' mod='shortcodes'}
            </label>
            <div class="col-lg-9">{$shortcodes->shortcode_name}</div>
        </div>
        <div class="form-group clearfix">
            <label class="col-lg-3">{l s='Lastname:' mod='shortcodes'}</label>
            <div class="col-lg-9">{$shortcodes->shortcode_description}</div>
        </div>
        <div class="form-group clearfix">
            <label class="col-lg-3">{l s='E-mail:' mod='shortcodes'}</label>
            <div class="col-lg-9">{$shortcodes->shortcode_content}</div>
        </div>
        <div class="form-group clearfix">
            <label class="col-lg-3">{l s='Product:' mod='shortcodes'}</label>
            <div class="col-lg-9">{$shortcodes->shortcode_status}</div>
        </div>
    </div>
</fieldset>
