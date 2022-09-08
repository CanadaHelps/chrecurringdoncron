<?php

function civicrm_api3_job_email_initiator_recurring_contribution_last_hour($params) {
    
    $params = array();
    $params['from'] = 'From Example <wellnessave@canadahelps.ca>';
    $params['toName'] = 'To  Example';
    $params['toEmail'] = 'test.chhelp29@gmail.com';
    $params['subject'] = 'Subject Example';
    $params['text'] = 'Example text';
    $params['html'] = '<p>Example HTML</p>';
    CRM_Utils_Mail::send($params);

    return civicrm_api3_create_success();
  }