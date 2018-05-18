var Checkout=Class.create();Checkout.prototype={initialize:function(urls){this.reviewUrl=urls.review;this.saveMethodUrl=urls.saveMethod;this.failureUrl=urls.failure;this.billingForm=false;this.shippingForm=false;this.syncBillingShipping=false;this.method='';this.payment='';this.loadWaiting=false;this.steps=['login','billing','shipping','shipping_method','payment','review'];labels=document.getElementsByTagName("button");for(var i=0;i<labels.length;i++)
{if(labels[i].className=='button btn-proceed-checkout btn-checkout')
labels[i].onclick=showCheckout;}
function showCheckout(){ScrollToControl('scroll');}},ajaxFailure:function(){location.href=this.failureUrl;},loadingbox:function()
{$("oscheckout-review").update('<div class="ajax-load">&nbsp;</div>')},loadReview:function(step){var request=new Ajax.Request(this.reviewUrl,{method:'post',parameters:Form.serialize('oscheckout-form'),onLoading:this.loadingbox.bind(this),onComplete:this.onComplete,onSuccess:function(transport){if(transport.status==200){$("oscheckout-review").update(transport.responseText)}},onFailure:checkout.ajaxFailure.bind(checkout)});}}
var Payment=Class.create();Payment.prototype={initialize:function(reviewUrl,paymentUrl){this.reviewUrl=reviewUrl;this.paymentUrl=paymentUrl;},ajaxFailure:function(){},loadingbox:function(){$("oscheckout-review").update('<div class="ajax-load">&nbsp;</div>')},ajaxSucess:function(){Element.hide('payment-please-wait');},loadReview:function(step){var updater=new Ajax.Updater('oscheckout-review',this.reviewUrl,{method:'get',onLoading:this.loadingbox.bind(this),onFailure:this.ajaxFailure.bind(this),onComplete:this.ajaxSucess.bind(this)});},reloadPaymentBlock:function(step){var updater=new Ajax.Updater('ajax-payment-methods',this.paymentUrl,{method:'get',onLoading:this.loadingbox.bind(this),onFailure:this.ajaxFailure.bind(this),onSuccess:function(){},onComplete:function(){payment.switchMethod(paymentMethod);}});},switchMethod:function(method){if(this.currentMethod&&$('payment_form_'+this.currentMethod)){this.changeVisible(this.currentMethod,true);}
if($('payment_form_'+method)){this.changeVisible(method,false);$('payment_form_'+method).fire('payment-method:switched',{method_code:method});}else{}
this.currentMethod=method;this.loadReview('payment');},changeVisible:function(method,mode){var block='payment_form_'+method;[block+'_before',block,block+'_after'].each(function(el){element=$(el);if(element){element.style.display=(mode)?'none':'';element.select('input','select','textarea').each(function(field){field.disabled=mode;});}});}}
var Billing=Class.create();Billing.prototype={initialize:function(form,addressUrl,saveUrl){this.form=form;if($(this.form)){$(this.form).observe('submit',function(event){this.save();Event.stop(event);}.bind(this));}
this.addressUrl=addressUrl;this.saveUrl=saveUrl;this.onAddressLoad=this.fillForm.bindAsEventListener(this);},setAddress:function(addressId){if(addressId){request=new Ajax.Request(this.addressUrl+addressId,{method:'get',onSuccess:this.onAddressLoad,onFailure:checkout.ajaxFailure.bind(checkout)});}
else{this.fillForm(false);}},newAddress:function(isNew){if(isNew){this.resetSelectedAddress();Element.show('billing-new-address-form');}else{Element.hide('billing-new-address-form');}},resetSelectedAddress:function(){var selectElement=$('billing-address-select')
if(selectElement){selectElement.value='';}},fillForm:function(transport){var elementValues={};if(transport&&transport.responseText){try{elementValues=eval('('+transport.responseText+')');}
catch(e){elementValues={};}}
else{this.resetSelectedAddress();}
arrElements=Form.getElements(this.form);for(var elemIndex in arrElements){if(arrElements[elemIndex].id){var fieldName=arrElements[elemIndex].id.replace(/^billing:/,'');arrElements[elemIndex].value=elementValues[fieldName]?elementValues[fieldName]:'';if(fieldName=='country_id'&&billingForm){billingForm.elementChildLoad(arrElements[elemIndex]);}}}},setUseForShipping:function(flag){$('shipping:same_as_billing').checked=flag;}}
var Review=Class.create();Review.prototype={initialize:function(form,saveUrl,successUrl,agreementsForm){this.form=form;this.saveUrl=saveUrl;this.successUrl=successUrl;this.agreementsForm=agreementsForm;this.onSave=this.nextStep.bindAsEventListener(this);this.onComplete=this.resetLoadWaiting.bindAsEventListener(this);},loadingbox:function(){$("review-require").update(' <div class="please-wait-loading">&nbsp;</div>')
var form=$('review-btn');form.disabled='true';},save:function(){var validator=new Validation(this.form);if(validator.validate()){var request=new Ajax.Request(this.saveUrl,{method:'post',parameters:Form.serialize(this.form),onLoading:this.loadingbox.bind(this),onComplete:this.onComplete,onSuccess:function(transport){if(transport.status==200){var data=transport.responseText.evalJSON();if(!data.success)
{alert(data.error_messages);$("review-require").update('');$('review-btn').disabled='';}
if(data.redirect){location.href=data.redirect;return;}
if(data.success){this.isSuccess=true;window.location=data.success;}}},onFailure:checkout.ajaxFailure.bind(checkout)});}},resetLoadWaiting:function(transport){},nextStep:function(transport){if(transport&&transport.responseText){try{response=eval('('+transport.responseText+')');}
catch(e){response={};}
if(response.redirect){location.href=response.redirect;return;}
if(response.success){this.isSuccess=true;window.location=this.successUrl;}
else{var msg=response.error_messages;if(typeof(msg)=='object'){msg=msg.join("\n");}
if(msg){alert(msg);}}
if(response.update_section){$('checkout-'+response.update_section.name+'-load').update(response.update_section.html);}
if(response.goto_section){checkout.gotoSection(response.goto_section);checkout.reloadProgressBlock();}}},isSuccess:false}
var Shipping=Class.create();Shipping.prototype={initialize:function(form,addressUrl,methodsUrl,reloadUrl){this.form=form;this.addressUrl=addressUrl;this.reloadUrl=reloadUrl;this.methodsUrl=methodsUrl;this.onAddressLoad=this.fillForm.bindAsEventListener(this);},setAddress:function(addressId){if(addressId){request=new Ajax.Request(this.addressUrl+addressId,{method:'get',onSuccess:this.onAddressLoad,onFailure:checkout.ajaxFailure.bind(checkout)});}
else{this.fillForm(false);}},newAddress:function(isNew){if(isNew){this.resetSelectedAddress();Element.show('shipping-new-address-form');}else{Element.hide('shipping-new-address-form');}},resetSelectedAddress:function(){var selectElement=$('shipping-address-select')
if(selectElement){selectElement.value='';}},fillForm:function(transport){var elementValues={};if(transport&&transport.responseText){try{elementValues=eval('('+transport.responseText+')');}
catch(e){elementValues={};}}
else{this.resetSelectedAddress();}
arrElements=Form.getElements(this.form);for(var elemIndex in arrElements){if(arrElements[elemIndex].id){var fieldName=arrElements[elemIndex].id.replace(/^shipping:/,'');arrElements[elemIndex].value=elementValues[fieldName]?elementValues[fieldName]:'';if(fieldName=='country_id'&&shippingForm){shippingForm.elementChildLoad(arrElements[elemIndex]);}}}},setSameAsBilling:function(flag){var value;var address;$('shipping:same_as_billing').checked=flag;value=$('shipping:address_id').value
address=$('shipping:has_addresss').value
if(flag)
{if((value)&&(address!=0))
{Element.hide('shipping-new-address-form');Element.hide('shipping-old-address-form');}
else if(value)
{Element.hide('shipping-new-address-form');}
else
{Element.hide('shipping-new-address-form');}}
else
{if((value)&&(address!=0))
{Element.show('shipping-old-address-form');}
else if(value)
{Element.show('shipping-new-address-form');}
else
{Element.show('shipping-new-address-form');}}},syncWithBilling:function(){},loadingbox:function(){$("oscheckout-review").update('<div class="ajax-load">&nbsp;</div>')},loadReview:function(){var updater=new Ajax.Updater('oscheckout-review',this.reloadUrl,{method:'post',onLoading:this.loadingbox.bind(this),parameters:Form.serialize(this.form)});},setRegionValue:function(){$('shipping:region').value=$('billing:region').value;}}
var ShippingMethod=Class.create();ShippingMethod.prototype={initialize:function(form){this.form=form;}}
function changeCheckoutMethod()
{if($('login:guest').checked)
{Element.hide('passowrd-container');$('register_customer').value='';}
else
{Element.show('passowrd-container');$('register_customer').value='register';}}
window.onload=function(){for(var i=0,l=document.getElementsByTagName('input').length;i<l;i++){if(document.getElementsByTagName('input').item(i).type=='text'){document.getElementsByTagName('input').item(i).setAttribute('autocomplete','off');};};};function elementPosition(obj){var curleft=0,curtop=0;if(obj.offsetParent){curleft=obj.offsetLeft;curtop=obj.offsetTop;while(obj=obj.offsetParent){curleft+=obj.offsetLeft;curtop+=obj.offsetTop;}}
return{x:curleft,y:curtop};}
function ScrollToControl(id)
{var elem=document.getElementById(id);var scrollPos=elementPosition(elem).y;scrollPos=scrollPos-document.documentElement.scrollTop;var remainder=scrollPos%50;var repeatTimes=(scrollPos-remainder)/50;ScrollSmoothly(scrollPos,repeatTimes);window.scrollBy(0,remainder);}
var repeatCount=0;var cTimeout;var timeoutIntervals=new Array();var timeoutIntervalSpeed;function ScrollSmoothly(scrollPos,repeatTimes)
{if(repeatCount<repeatTimes)
{window.scrollBy(0,50);}
else
{repeatCount=0;clearTimeout(cTimeout);return;}
repeatCount++;cTimeout=setTimeout("ScrollSmoothly('"+scrollPos+"','"+repeatTimes+"')",110);}