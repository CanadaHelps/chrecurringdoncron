<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'RecurringDonationCompliance',
    'class' => 'CRM_Chrecurringdoncron_DAO_RecurringDonationCompliance',
    'table' => 'civicrm_recurring_donation_compliance',
  ],
];
