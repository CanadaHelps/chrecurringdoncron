<?php
use CRM_Chrecurringdoncron_ExtensionUtil as E;

class CRM_Chrecurringdoncron_BAO_RecurringDonationCompliance extends CRM_Chrecurringdoncron_DAO_RecurringDonationCompliance {

  /**
   * Create a new RecurringDonationCompliance based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Chrecurringdoncron_DAO_RecurringDonationCompliance|NULL
   *
  public static function create($params) {
    $className = 'CRM_Chrecurringdoncron_DAO_RecurringDonationCompliance';
    $entityName = 'RecurringDonationCompliance';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
