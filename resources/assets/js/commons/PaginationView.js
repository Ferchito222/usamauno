import React, { PureComponent, Fragment } from 'react';
// import "babel-polyfill";

export default class PaginationView extends PureComponent {
  paginate = (c, m) => {
    if (m === 1) {
      return [1];
    }

    let delta = 2;
    let range = [];
    let rangeWithDots = []
    let l;

    range.push(1)
    for (let i = c - delta; i <= c + delta; i++) {
      if (i < m && i > 1) {
        range.push(i);
      }
    }
    range.push(m);

    for (let i of range) {
      if (l) {
        if (i - l === 2) {
          rangeWithDots.push(l + 1);
        } else if (i - l !== 1) {
          rangeWithDots.push('...');
        }
      }
      rangeWithDots.push(i);
      l = i;
    }

    return rangeWithDots;
  }

  render() {
    const { pagination } = this.props;
    const items = this.paginate(pagination.currentPage, pagination.lastPage);

    return (
      <Fragment>
        {
          pagination.lastPage > 0 && (
            <nav role="navigation">
              <ul className="cd-pagination">
                <li className="button">
                  <a href="#!" className={pagination.currentPage === 1 ? 'disabled' : ''} onClick={() => this.props.handlePage(pagination.currentPage - 1)}>
                    Prev
                  </a>
                </li>
                {
                  items.map((item, i) => 
                    <li key={i}>
                      {
                        Number.isInteger(item) ? (
                          <a href="#!" className={`${item === pagination.currentPage ? 'current' : ''}`} onClick={() => this.props.handlePage(item)}>
                            {item}
                          </a>
                        ) : (
                          <span>{item}</span>
                        )
                      }
                    </li>
                  )
                }
                <li className="button">
                  <a href="#!" className={pagination.currentPage === pagination.lastPage ? 'disabled' : ''} onClick={() => this.props.handlePage(pagination.currentPage + 1)}>
                    Next
                  </a>
                </li>
              </ul>
            </nav>
          )
        }
      </Fragment>
    )
  }
}