import React from 'react';

export const PaginationView = (props) => {
  const { pagination } = props;

  return (
    <div className="Pagination">
      <a
        href=""
        className="Pagination-button"
        onClick={(e) => props.handleFetchData(e, (pagination.get('currentPage') - 1))}
      >
        Anterior
      </a>
      <span className="Pagination-counter">
        {pagination.get('currentPage')} / {pagination.get('lastPage')}
      </span>
      <a
        href=""
        className="Pagination-button"
        onClick={(e) => props.handleFetchData(e, (pagination.get('currentPage') + 1))}
      >
        Siguiente
      </a>
    </div>
  )
}