import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { HashRouter, Switch, Route } from 'react-router-dom';
import _ from 'underscore';
import { eLoading } from 'helpers';

import * as actions from './../../actions/policy-edit';
import { PolicyEditListView } from './../../components/policy-edit/PolicyEditListView';
import { PolicyEditDeView } from './../../components/policy-edit/PolicyEditDeView';

class PolicyEditContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      complete: false,
    };

    this.getData = this.getData.bind(this);
    this.handleFetchData = this.handleFetchData.bind(this);
  }

  componentDidMount = () => {
    this.getData();
  }

  getData = () => {
    eLoading(true);

    const $url = document.getElementById('r-ope-s').value;

    axios.get($url, {
      params: {}
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const { api, data } = response.data;

          this.props.actions.setData(api, data.users, data.products);

          this.setState({ complete: true });
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  handleFetchData = (e, page) => {
    e.preventDefault();
    
    this.props.actions.fetchData(page);
  }

  render() {
    const { complete } = this.state;
    const { headers, details, lists, users, products, header, pagination, filter } = this.props;

    return (
      <HashRouter>
        {
          complete &&
          <Switch>
            <Route
              exact path="/"
              render={(props) => 
                <PolicyEditListView
                  {...props}
                  lists={lists}
                  details={details}
                  users={users}
                  products={products}
                  pagination={pagination}
                  filter={filter}
                  handleFetchData={this.handleFetchData}
                  handleFilterData={this.props.actions.filterData}
                  handleResetPagination={this.props.actions.resetPagination}
                  handleSetHeader={this.props.actions.setHeader}
                />
              }
            />
            
            <Route
              exact path="/edit/de/:id"
              render={(props) => 
                <PolicyEditDeView
                  {...props}
                  header={header}
                  handleSetHeader={this.props.actions.setHeader}
                  handleUpdateHeader={this.props.actions.updateHeader}
                />
              }
            />
          </Switch>
        }
      </HashRouter>
    )
  }
}

const mapStateToProps = (state, props) => {
  return {
    headers: state.get('policyEdit').get('headers'),
    details: state.get('policyEdit').get('details'),
    lists: state.get('policyEdit').get('lists'),
    users: state.get('policyEdit').get('users'),
    products: state.get('policyEdit').get('products'),
    header: state.get('policyEdit').get('header'),
    pagination: state.get('policyEdit').get('pagination'),
    filter: state.get('policyEdit').get('filter'),
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(PolicyEditContainer);