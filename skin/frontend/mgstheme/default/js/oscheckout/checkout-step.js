document.observe('dom:loaded',function()
{var shipping_address_select=$('shipping-address-select');var billing_address_select=$('billing-address-select');var billing_region_id=$('billing:region_id');var shipping_region_id=$('shipping:region_id');$('billing:country_id').observe('change',function(){updateCheckoutMethod()});if($('for-shipping').value==1){$('shipping:country_id').observe('change',function(){updateCheckoutMethod()});if($('customer-loggedin').value==1){if(shipping_address_select!==null){Event.stopObserving('shipping-address-select','change');$('shipping-address-select').observe('change',function(){updateCheckoutMethod()});}}}
if(billing_region_id!=null){$('billing:region_id').observe('change',function(){updateCheckoutMethod()});$('billing:region').observe('change',function(){updateCheckoutMethod()});}
if($('for-shipping').value==1){if(shipping_region_id!=null){$('shipping:region_id').observe('change',function(){updateCheckoutMethod()});$('shipping:region').observe('change',function(){updateCheckoutMethod()});}
if($('customer-loggedin').value==1){if(shipping_address_select!==null){Event.stopObserving('shipping-address-select','change');$('shipping-address-select').observe('change',function(){updateCheckoutMethod()});}}}
if($('customer-loggedin').value==1){if(billing_address_select!==null){Event.stopObserving('billing-address-select','change');$('billing-address-select').observe('change',function(){updateCheckoutMethod()});}}
$('billing:postcode').observe('change',function(){updateCheckoutMethod()});if($('for-shipping').value==1){$('shipping:postcode').observe('change',function(){updateCheckoutMethod()});if($('customer-loggedin').value==1){if(shipping_address_select!==null){Event.stopObserving('shipping-address-select','change');$('shipping-address-select').observe('change',function(){updateCheckoutMethod()});}}}
if($('customer-loggedin').value==1){if(billing_address_select!==null){Event.stopObserving('billing-address-select','change');$('billing-address-select').observe('change',function(){updateCheckoutMethod()});}}
$('billing:city').observe('change',function(){updateCheckoutMethod()});if($('for-shipping').value==1){$('shipping:city').observe('change',function(){updateCheckoutMethod()});if($('customer-loggedin').value==1){if(shipping_address_select!==null){Event.stopObserving('shipping-address-select','change');$('shipping-address-select').observe('change',function(){updateCheckoutMethod()});}}}
if($('customer-loggedin').value==1){if(billing_address_select!==null){Event.stopObserving('billing-address-select','change');$('billing-address-select').observe('change',function(){updateCheckoutMethod()});}}
function updateCheckoutMethod()
{var shipment_methods=$('ajax-shipping-method');var payment_methods=$('ajax-payment-methods');var totals=$('oscheckout-review');var shipment_methods_found=false;var defaultvalut=0;if(typeof shipment_methods!='undefined'&&shipment_methods!=null)
{shipment_methods_found=true;}
if(shipment_methods_found)
{shipment_methods.update('<div class="ajax-load">&nbsp;</div>');}
payment_methods.update('<div class="ajax-load">&nbsp;</div>');totals.update('<div class="ajax-load">&nbsp;</div>');this.saveUrl=$('save-billing-url').value;var request=new Ajax.Request(this.saveUrl,{method:'post',parameters:Form.serialize('oscheckout-form'),onSuccess:function(transport)
{if(transport.status==200)
{var data=transport.responseText.evalJSON();if(shipment_methods_found)
{shipment_methods.update(data.shipping_method);}
payment_methods.update(data.payment_method);if(defaultvalut==0)
{payment.switchMethod(paymentMethod);defaultvalut=1;}
checkout.loadReview();}}});}});if($('customer-loggedin').value==0){Event.observe(window,'load',function(){var button=$('oscheckout-login-button');var loginButtonFunction=function(e){var loading=$('oscheckout-login-loading');var error=$('oscheckout-login-error');$('login-container').hide();error.hide();loading.show();var form=$('oscheckout-login-form-detail');var url=$('login-action-url').value;new Ajax.Request(url,{parameters:form.serialize(true),method:'POST',onComplete:function(transport){if(transport.status==200){var result=transport.responseText.evalJSON();if(!result.success){loading.hide();error.update(result.error);error.show();$('login-container').show();}
else{window.location=window.location;}}}})};var onkeypressHandler=function(event){if(event.keyCode==Event.KEY_RETURN){event.preventDefault();loginButtonFunction();}};button.observe('click',loginButtonFunction);});}
var myForm=new VarienForm('giftoption',true);var lightbox;function openLightbox(Id)
{lightbox=new Lightbox(Id);lightbox.open();}
function closeLightbox()
{var error=$('oscheckout-login-error');var forgot_box=$('oscheckout-forgot-form');var login_box=$('oscheckout-login-form');error.hide();login_box.show();lightbox.close();}