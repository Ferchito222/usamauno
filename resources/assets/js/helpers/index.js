export const eLoading = (show) => {
  const loaderWrapper = document.getElementById('Loader-wrapper');
  if (show) {
    loaderWrapper.style.display = 'block';
  } else {
    loaderWrapper.style.display = 'none';
  }
}

export const closeModal = () => {
  document.getElementById('Modal-close').click();
}