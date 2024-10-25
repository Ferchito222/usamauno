import React from 'react';
import UserStack from './user/UserStack.jsx';
import UserImport from './user/UserImport';
import EmailStack from './email/EmailStack.jsx';
import AssignmentStack from './assignment-wallet/AssignmentStack.jsx';
import PolicyEditStack from './policy-edit/PolicyEditStack.jsx';
import AttachedCertificateStack from './attached/AttachedCertificateStack.jsx';
import CancellationStack from './cancellation/CancellationStack.jsx';

const App = () => {
  const element = document.getElementById('adm-flux');
  
  if ('user' in element.dataset) {
    return <UserStack />;
  }

  if ('userImport' in element.dataset) {
    return <UserImport />;
  }
  
  if ('email' in element.dataset) {
    return <EmailStack />;
  }
  
  if ('assignmentWallet' in element.dataset) {
    return <AssignmentStack />;
  }
  
  if ('policyEdit' in element.dataset) {
    return <PolicyEditStack />;
  }
  
  if ('attachedCertificate' in element.dataset) {
    return <AttachedCertificateStack />;
  }
  
  if ('cancel' in element.dataset) {
    return <CancellationStack />;
  }

  return (<div></div>);
};

export default App;