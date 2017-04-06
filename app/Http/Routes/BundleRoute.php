<?php


Route::group(['middleware' => ['auth','user.status']], function () {
  $prefix = '/bundle';
  $controller = 'BundleController';

  Route::get("${prefix}",     "${controller}@index");
  Route::get("${prefix}/{id}/add",    "${controller}@cartStore");
  Route::get("${prefix}/{ucode}/addUcode",   "${controller}@addUcode");
  Route::get("${prefix}/{ucode}/removeUcode",   "${controller}@removeUcode");
  Route::get("${prefix}/view",   "${controller}@cartView");
  Route::get("${prefix}/cart/{id}/delete",   "${controller}@cartDelete");
  Route::get("${prefix}/cart/delete",   "${controller}@cartDeleteAll");
  Route::get("${prefix}/ucode/{ucode}",   "${controller}@viewUCode");
  Route::get("${prefix}/mediasortorder",   "${controller}@mediaSortInBundleBuilder");
  Route::get("${prefix}/ucodepdf",   "${controller}@ucodePdfGenerate");
  Route::get("${prefix}/ucodecopyclipboard",   "${controller}@ucodeCopytoClipboard");
  Route::get("${prefix}/ucodepdfdownload/{ucodeid}",   "${controller}@ucodeDownloadPdf");
  
  Route::post("${prefix}/cart/store",   "${controller}@ucodeStore");
  Route::post("${prefix}/action",   "${controller}@bundleAction");

  Route::patch("${prefix}",   "${controller}@patchBundlebuilder");
  
  Route::delete("${prefix}",   "${controller}@deleteUcodes");
  
});
