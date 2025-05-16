<?php
function createPagination(
  int $totalRecords,
  int $recordsPerPage,
  int $currentPage,
  string $baseUrl,
  int $maxLinks = 11
) {

  $totalPages = (int)ceil($totalRecords / $recordsPerPage);

  $html = '<nav class="d-flex gap-3 align-items-center justify-content-between mb-3">';
  $html .= '<ul class="pagination mb-0">';

  $disabled = $currentPage === 1 ? 'disabled' : '';
  $previews = max(($currentPage - 1), 1);
  $html .= '<li class="page-item ' . $disabled . '"><a class="page-link" href="' . $baseUrl . '&page=' . $previews . '">Previous</a></li>';

  $startPage = (int) max(1, $currentPage - floor($maxLinks / 2));
  $endPage = min($startPage + $maxLinks - 1, $totalPages);

  if (($endPage - $startPage + 1) < $maxLinks) {
    $startPage = max(1, $endPage - $maxLinks + 1);
  }

  for ($i = $startPage; $i <= $endPage; $i++) {

    if ($i === $currentPage) {
      $html .= '<li class="page-item"><span class="page-link active">' . $i . '</span></li>';
    } else {
      $html .=  '<li class="page-item"><a class="page-link" href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a></li>';
    }
  }
  
  $disabled = $currentPage === $totalPages ? 'disabled' : '';
  $hidden = $totalPages == 1  ? 'd-none' : '';
  $next = min(($currentPage + 1), $totalPages);
  $html .= '<li class="page-item ' . $disabled . ' ' . $hidden . '"><a class="page-link" href="' . $baseUrl . '&page=' . $next . '">Next</a></li>';
  $html .= '</ul><p style="margin-bottom:0; line-height:1;">' . $totalRecords . ' records found <br>Page '.$currentPage.' of '.$totalPages.'</p></nav>';

  return $html;
}
