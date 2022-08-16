<?php
namespace AL\Forum\Controller;

use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication; 

use TYPO3\CMS\Core\Authentication\AuthenticationService;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyAuthenticatorService extends AuthenticationService  
{
   public function init(): bool
    {
        return true;
    }

   public function reset() : void
   {
      // If needed: clean up and forget values of former authentications
   }

   public function authUser(array $user): int
  {
   
      if (!$isValidPassword) {
          // Failed login attempt - wrong password
          $this->writeLogMessage(TYPO3_MODE . ' Authentication failed - wrong password for username \'%s\'', $submittedUsername);
          $message = 'Login-attempt from ###IP###, username \'%s\', password not accepted!';
          $this->writelog(255, 3, 3, 1, $message, [$submittedUsername]);
          $this->logger->info(sprintf($message, $submittedUsername));
          // Responsible, authentication failed, do NOT check other services
          //check submitted hashed password (login for activation)
          if($submittedPassword == $passwordHashInDatabase) {
              return 200;
          }
          return 0;
      }

  }

   /**
    * returns false or the user array
    **/ 
   public function getUser() : mixed
   {
      // is called to get additional information after login.
   }
   
   
   
   
   
   
public static function loginUser($user, $password)
{
    $_POST['logintype'] = 'login';
    $_POST['user'] = $user;
    $_POST['pass'] = $password;
    $authService = GeneralUtility::makeInstance(FrontendUserAuthentication::class); 
    $authService->start();
}
   
   
   
}