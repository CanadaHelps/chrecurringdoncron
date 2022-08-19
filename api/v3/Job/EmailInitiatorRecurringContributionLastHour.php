<?php

/**
 * Job.EmailInitiatorRecurringContributionLastHour API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_job_email_initiator_recurring_contribution_last_hour($params) {
    
    $params = array();
    $params['from'] = 'From Example <wellnessave@canadahelps.ca>';
    $params['toName'] = 'To Example';
    $params['toEmail'] = 'test.chhelp29@gmail.com';
    $params['subject'] = 'Subject Example';
    $params['text'] = 'Example text';
    $params['html'] = '<p>Example HTML</p>';
    CRM_Utils_Mail::send($params);

    return civicrm_api3_create_success();
  }