<?php

require_once 'chrecurringdoncron.civix.php';
// phpcs:disable
use CRM_Chrecurringdoncron_ExtensionUtil as E;
use CRM_Chrecurringdoncron_ExtensionUtils as ER;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function chrecurringdoncron_civicrm_config(&$config) {
  _chrecurringdoncron_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function chrecurringdoncron_civicrm_install() {
  _chrecurringdoncron_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function chrecurringdoncron_civicrm_postInstall() {
  _chrecurringdoncron_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function chrecurringdoncron_civicrm_uninstall() {
  _chrecurringdoncron_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function chrecurringdoncron_civicrm_enable() {
  _chrecurringdoncron_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function chrecurringdoncron_civicrm_disable() {
  _chrecurringdoncron_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function chrecurringdoncron_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _chrecurringdoncron_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function chrecurringdoncron_civicrm_entityTypes(&$entityTypes) {
  _chrecurringdoncron_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function chrecurringdoncron_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function chrecurringdoncron_civicrm_navigationMenu(&$menu) {
//  _chrecurringdoncron_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _chrecurringdoncron_civix_navigationMenu($menu);
//}

/**
 * Implementation of hook_civicrm_buildForm
 *
 */
function chrecurringdoncron_civicrm_buildForm($formName, &$form) {
  if($formName == 'CRM_Contribute_Form_Contribution')
  {
    if (!empty($form->_mode) && $form->_mode == 'live') {
       //CRM-1448- Make "Email" mandatory for credit card recurring contributions.
      $form->add('text', 'billingEmailField', ts('Billing Email'),array('class' => 'crm-select2'));
      $paymentProcessorCreditCards = json_encode(ER::getCreditcardPaymentProcessorValue());
      $billingEmailFieldID = 'billingEmailField';
      $billingNameAddSection = 'billing_name_address-section';
      CRM_Core_Region::instance('page-body')->add(array(
        'script' => "
        var PaymentProcessorNameList = $paymentProcessorCreditCards;
        setTimeout(function waitDuration() {
          paymentDisplay(cj('#payment_processor_id').find(\"option:selected\").text());
          cj('#payment_processor_id').on('change', function() {
            setTimeout(paymentDisplay, 600,cj(this).find(\"option:selected\").text());
          });

          cj('#is_recur').change(function() {
            loadEmailField(cj('#payment_processor_id').find(\"option:selected\").text());
          });

          cj('#contact_id').on('change', function() {
            loadEmailField(cj('#payment_processor_id').find(\"option:selected\").text());
          });
        });

        function checkValue(value, arr) {
          var status = 0;
          for (var i = 0; i < arr.length; i++) {
            var name = arr[i];
            if (name == value) {
              status = 1;
              break;
            }
          }
          return status;
        }

        function loadEmailField(ar)
        {
          if (checkValue(cj('#payment_processor_id').find(':selected').text(), PaymentProcessorNameList)) {
            if(checkValue(cj('#payment_processor_id').find(':selected').text(), PaymentProcessorNameList) && cj('#is_recur').prop('checked') == true){
              var cid = cj('#contact_id').val();
              if(cid){
                loadEmailAddressFieldData(cid);
              }else{
                cj('.$billingNameAddSection').find('div:nth-child(9)').show();
                additionalOnlyField();
              }
            }else if(checkValue(cj('#payment_processor_id').find(':selected').text(), PaymentProcessorNameList) && cj('#is_recur').prop('checked') == false){
              cj('.$billingNameAddSection').find('div:nth-child(9)').hide();
            }
          }else{
            cj('.$billingNameAddSection').find('div:nth-child(9)').hide();
          }
        }

          function additionalOnlyField()
          {
            if ( !cj( '#$billingEmailFieldID' ).length ) {
              cj('.$billingNameAddSection').find('div:nth-child(9)').find('.content').after('<input size=\"30\" maxlength=\"60\"  class=\"required crm-form-text\" name=\"$billingEmailFieldID\" type=\"text\" id=\"$billingEmailFieldID\" >');
            }
          }

          function paymentDisplay(pp) {
            addBillingEmailField();
            cj('.$billingNameAddSection').find('div:nth-child(9)').hide();
            loadEmailField(pp);
          }
          function addBillingEmailField()
          {
            if( cj( '.crm-section billing_custom_email_filed' ).length > 0 ) {

            }else
            {
              cj('.billing_postal_code-5-section').after('<div class=\"crm-section billing_custom_email_filed\"><div class=\"label\"><label for=\"$billingEmailFieldID\">Email</label><span class=\"crm-marker\" title=\"This field is required.\">*</span></div><div class=\"content\"></div><div class=\"clear\"></div><span style=\"line-height: 1.5em;margin-top: 5px;display: inline-block; margin-left: 189px; width: 493px; max-width: 559px;\">In order to process a recurring contribution using a credit card, donors must provide a valid email address.</span></div>');
            }
          }
          function loadEmailAddressFieldData(contactID)
          {
            CRM.api3('Email', 'get', {
              \"contact_id\": contactID
            }).then(function(result) {
            cj('#$billingEmailFieldID').remove();
            cj('.$billingNameAddSection').find('div:nth-child(9)').show();
          if(result.count >= 1)
          {
            cj('.billing_name_address-group').find('div:nth-child(9)').find('.content').after('<select   class=\"crm-select2 crm-form-select\" name=\"$billingEmailFieldID\" id=\"$billingEmailFieldID\"  ></select>');
          }
          if(result.count == 0)
          {
            cj('.billing_name_address-group').find('div:nth-child(9)').find('.content').after('<input size=\"30\" maxlength=\"60\"  class=\"required crm-form-text\" name=\"$billingEmailFieldID\" type=\"text\" id=\"$billingEmailFieldID\" >');
          }
          cj.each(result, function(key, value) {
                if(key == 'values' && result.count >= 1)
                {
                  cj.each(value, function(k, v) {
                    if(v.is_primary == 1)
                    {
                      cj('#$billingEmailFieldID').prepend(cj('<option>', { value: v.email,text : v.email ,selected: true}));
                    }else{
                      cj('#$billingEmailFieldID').append(cj('<option>', { value: v.email,text : v.email }));
                    }
                  });
                }
              });
            });
          }
        ",
      ));
    }
  }
}

function chrecurringdoncron_civicrm_postProcess($formName, &$form) {
 
  if($formName == 'CRM_Contribute_Form_Contribution')
  {
    //CRM-1448- Make Email field set as Main Primary
    $params = CRM_Utils_Request::exportValues();
    if ($form->getVar('_mode') && $form->getVar('_mode') == 'live') {
      $paymentProcessorCreditcardList = ER::getCreditcardPaymentProcessorValue();
      $paymentProcessor = $form->getVar('_paymentProcessor');
      if(!empty($paymentProcessor['name']) && (in_array($paymentProcessor['name'],$paymentProcessorCreditcardList)) && ($params['is_recur'] == 1)){
        $params = CRM_Utils_Request::exportValues();
        if (!empty($params['billingEmailField'])) {
          $email_address = $params['billingEmailField'];
          $contactID = $params['contact_id'];

          //Check if email address exists or not
          $getContactEmailAddress = civicrm_api3('Email', 'get', [
            'sequential' => 1,
            'contact_id' => $params['contact_id'],
          ]);
          if(($getContactEmailAddress['values'])&& $getContactEmailAddress['count']!= 0)
          {
            $getThePrimaryEmail = civicrm_api3('Email', 'get', [
              'sequential' => 1,
              'contact_id' => $contactID,
              'is_primary' => 1,
              'return' => ["email"],
            ]);
            if(($getThePrimaryEmail['values'])&& $getThePrimaryEmail['count']== 1){
              if($getThePrimaryEmail['values']['email'] != $email_address)
              {
                $result = civicrm_api3('Email', 'create', [
                  'contact_id' => $contactID ,
                  'email' => $email_address,
                  'is_primary' => 1,
                  'location_type_id' => "Main",
                  'id' => $getThePrimaryEmail['id'],
                ]);
              }
            }
          }else{
            //Create new email address field and make it main primary 
            $result = civicrm_api3('Email', 'create', [
              'contact_id' => $contactID ,
              'email' => $email_address,
              'is_primary' => 1,
              'is_billing' => 1,
              'location_type_id' => "Main",
            ]);
          }
        }
      }
    }
    // Redirect to the View page on Edit contribution
    $contactID = $form->getVar('_contactID');
    $objectId = $form->getVar('_id');
    $qf_default = $form->getVar('_submitValues')['_qf_default'];
    if ($qf_default) {
      if($contactID && $qf_default == "Contribution:upload")
      {
        $entryUrl = $form->getVar('_params')['entryURL'];
        if(isset($entryUrl))
        {
          $parseEntryUrl = parse_url($entryUrl);
          parse_str(html_entity_decode($parseEntryUrl['query']));
          if(isset($cid) && $cid){
            CRM_Utils_System::redirect('/dms/contact/view?reset=1&context=search&redirect=contributionTab&cid='.$contactID);
          }
        }
        CRM_Utils_System::redirect('/dms/contact/view/contribution?reset=1&id='.$objectId.'&cid='.$contactID.'&action=view&context=search&selectedChild=contribute');
      }
    }
  }
}
function chrecurringdoncron_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors){
  if($formName == 'CRM_Contribute_Form_Contribution') {
    //CRM-1448- Make Email field manadatory for creditcard and recurring donations
    if ($form->getVar('_mode') && $form->getVar('_mode') == 'live') {
      $paymentProcessorCreditcardList = ER::getCreditcardPaymentProcessorValue();
      $paymentProcessor = $form->getVar('_paymentProcessor');
      $submitValue = $form->getVar('_submitValues');
      $params = CRM_Utils_Request::exportValues();
      if(!empty($paymentProcessor['name']) && (in_array($paymentProcessor['name'], $paymentProcessorCreditcardList)) && ($params['is_recur'] == 1)){
        if(empty($fields['billingEmailField']))
        {
          $errors['billingEmailField'] = ts('Email address field can not be empty');
        }else if(isset($fields['billingEmailField']) &&!empty($fields['billingEmailField']))
        {
          if( !filter_var( $fields['billingEmailField'], FILTER_VALIDATE_EMAIL ) ) {
            $errors['billingEmailField'] = ts('Please enter a valid email address.');
          }
        }
      }
    }
  }
}
