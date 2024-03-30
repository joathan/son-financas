<?php

use Psr\Http\Message\ServerRequestInterface;

$app->get('/bill-pays', function () use ($app) {
  $view = $app->service('view.renderer');
  $repository = $app->service('bill-pay.repository');
  $auth = $app->service('auth');
  $bills = $repository->findByField('user_id', $auth->user()->id);
  return $view->render('bill-pays/list.html.twig', [
    'bills' => $bills
  ]);
}, 'bill-pays.list');

$app->get('/bill-pays/new', function () use ($app) {
  $view = $app->service('view.renderer');
  $auth = $app->service('auth');
  $categoryRepository = $app->service('category-cost.repository');
  $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());
  return $view->render('bill-pays/create.html.twig', [
    'categories' => $categories
  ]);
}, 'bill-pays.new');

$app->post('/bill-pays/store', function (ServerRequestInterface $request) use ($app) {
  $data = $request->getParsedBody();
  $repository = $app->service('bill-pay.repository');
  $categoryRepository = $app->service('category-cost.repository');

  $auth = $app->service('auth');

  $data['user_id'] = $auth->user()->id;
  $data['date_launch'] = dateParse($data['date_launch']);
  $data['value'] = numberParse($data['value']);
  $data['category_cost_id'] = $categoryRepository->findOneBy([
    'id' => $data['category_cost_id'],
    'user_id' => $auth->user()->getId()
  ])->id;
  $repository->create($data);
  return $app->route('bill-pays.list');
}, 'bill-pays.store');

$app->get('/bill-pays/{id}/edit', function (ServerRequestInterface $request) use ($app) {
  $view = $app->service('view.renderer');
  $repository = $app->service('bill-pay.repository');
  $categoryRepository = $app->service('category-cost.repository');

  $auth = $app->service('auth');

  $id = $request->getAttribute('id');
  $bill = $repository->findOneBy([
    'id' => $id,
    'user_id' => $auth->user()->getId()
  ]);

  $categories = $categoryRepository->findByField('user_id', $auth->user()->getId());
  return $view->render('bill-pays/edit.html.twig', [
    'bill' => $bill,
    'categories' => $categories,
  ]);
}, 'bill-pays.edit');

$app->post('/bill-pays/{id}/update', function (ServerRequestInterface $request) use ($app) {
  $repository = $app->service('bill-pay.repository');
  $categoryRepository = $app->service('category-cost.repository');

  $auth = $app->service('auth');

  $data = $request->getParsedBody();
  $id = $request->getAttribute('id');

  $data['user_id'] = $auth->user()->id;
  $data['date_launch'] = dateParse($data['date_launch']);
  $data['value'] = numberParse($data['value']);
  $data['category_cost_id'] = $categoryRepository->findOneBy([
    'id' => $data['category_cost_id'],
    'user_id' => $auth->user()->getId()
  ])->id;

  $repository->update([
    'id' => $id,
    'user_id' => $auth->user()->id
  ], $data);
  return $app->route('bill-pays.list');
}, 'bill-pays.update');

$app->get('/bill-pays/{id}/show', function (ServerRequestInterface $request) use ($app) {
  $view = $app->service('view.renderer');
  $repository = $app->service('bill-pay.repository');
  $id = $request->getAttribute('id');
  $auth = $app->service('auth');
  $bill = $repository->findOneBy([
    'id' => $id,
    'user_id' => $auth->user()->id
  ]);
  return $view->render('bill-pays/show.html.twig', [
    'bill' => $bill
  ]);
}, 'bill-pays.show');

$app->get('/bill-pays/{id}/delete', function (ServerRequestInterface $request) use ($app) {
  $repository = $app->service('bill-pay.repository');
  $id = $request->getAttribute('id');
  $auth = $app->service('auth');
  $repository->delete([
    'id' => $id,
    'user_id' => $auth->user()->id
  ]);
  return $app->route('bill-pays.list');
}, 'bill-pays.delete');
