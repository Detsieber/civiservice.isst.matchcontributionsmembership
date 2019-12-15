<?php
use CRM_Matchcontributionsmemberships_ExtensionUtil as E;

/**
 * Job.Matchcontributionsmemberships API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Matchcontributionsmemberships_spec(&$spec) {
  $spec['magicword']['api.required'] = 1;
}

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
      'options' => ['sort' => "receive_date DESC", 'limit' => 4],
    ]);

// Now search for an entry in civicrm_membership_payment
foreach ($opencontribution["values"] as $opencontributions) {
    $membership_payment = civicrm_api3('MembershipPayment', 'get', [
      'sequential' => 1,
      'return' => ["membership_id", "id"],
    ]);
// entry exists already
    if $membership_payment[count] > 0 return;
// entry doesn't exist yet: search for a membership
    else;
      $membership = civicrm_api3('Membership', 'getsingle', [
        'return' => ["id", "end_date", "status_id"],
        'contact_id' => $opencontribution[contact_id],
      ]);

      $result = civicrm_api3('MembershipPayment', 'create', [
        'membership_id' => $membership[id],
        'contribution_id' => $opencontribution[id],
      ]);

    endif;

}


  
  
  
   
 /* 
  
  if (array_key_exists('magicword', $params) && $params['magicword'] == 'sesame') {
    $returnValues = array(
      // OK, return several data rows
      12 => array('id' => 12, 'name' => 'Twelve'),
      34 => array('id' => 34, 'name' => 'Thirty four'),
      56 => array('id' => 56, 'name' => 'Fifty six'),
    );
    // ALTERNATIVE: $returnValues = array(); // OK, success
    // ALTERNATIVE: $returnValues = array("Some value"); // OK, return a single value

    // Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($returnValues, $params, 'NewEntity', 'NewAction');
  }
  else {
    throw new API_Exception(/*errorMessage*/ 'Everyone knows that the magicword is "sesame"', /*errorCode*/ 1234);
  }
}
*/
  
