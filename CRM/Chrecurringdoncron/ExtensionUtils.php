<?php

class CRM_Chrecurringdoncron_ExtensionUtils {
  //CRM-1448 Whenever new credit card processor gets added which requires 'Email' field mandatory under Mastercard compliance project add payment processor name here
  public static function getCreditcardPaymentProcessorValue()
  {
    $paymentProcessorNames = ['Credit Card','Stripe Credit Card'];
    return $paymentProcessorNames;
  }

}  
