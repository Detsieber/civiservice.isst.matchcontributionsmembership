<?php
use CRM_Matchcontributionsmemberships_ExtensionUtil as E;

/**
 * Job.Matchcontributionsmemberships API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */

function civicrm_api3_job_Matchcontributionsmemberships($params) {
// Get list of contributions with financial_type = Membership Fee
    $opencontribution = civicrm_api3('Contribution', 'get', [
      'sequential' => 1,
      'return' => ["id", "contact_id", "receive_date", "total_amount"],
      'financial_type_id' => "Membership Fee",
      'contribution_status_id' => "Completed",
      'options' => ['sort' => "receive_date DESC", 'limit' => 10],
    ]);

// Now search for an entry in civicrm_membership_payment
  foreach ($opencontribution["values"] as $opencontributions) {
    $membership_payment = civicrm_api3('MembershipPayment', 'get', [
      'sequential' => 1,
      'return' => ["membership_id", "id"],
    ]);
// entry exists already
    if ($membership_payment[count] > 0) {
    }
// entry doesn't exist yet: search for a membership
    else {
      $membership = civicrm_api3('Membership', 'getsingle', [
        'return' => ["id", "end_date", "status_id"],
        'contact_id' => $opencontribution[contact_id],
      ]);

      $result = civicrm_api3('MembershipPayment', 'create', [
        'membership_id' => $membership[id],
        'contribution_id' => $opencontribution[id],
      ]);
    }
  }
}
