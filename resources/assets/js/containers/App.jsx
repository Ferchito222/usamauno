import React from 'react';
import CancellationStack from './cancellation/CancellationStack.jsx';
import CreditLineStack from './credit-line/CreditLineStack.jsx';

const App = () => {
  const element = document.getElementById('app-flux');

  if ('cancellation' in element.dataset) {
    return <CancellationStack />;
  }
  
  if ('creditLine' in element.dataset) {
    return <CreditLineStack />;
  }

  return (<div></div>);
};

export default App;