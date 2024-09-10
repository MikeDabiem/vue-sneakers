<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$sneakers = file_get_contents('db/sneakers.json');
$sneakers_arr = json_decode($sneakers, true);

$likes = file_get_contents('db/likes.json');
$likes_arr = json_decode($likes, true);

$cart = file_get_contents('db/cart.json');
$cart_arr = json_decode($cart, true);

function itemAction($input_id, $ids_arr)
{
    $id_exists = false;

    if ($ids_arr) {
        foreach ($ids_arr as $item) {
            if ($item['id'] === $input_id) {
                $id_exists = true;
                break;
            }
        }
    }

    if ($id_exists) {
        $ids_arr = array_filter($ids_arr, function ($item) use ($input_id) {
            return $item['id'] !== $input_id;
        });
    } else {
        $ids_arr[] = ['id' => $input_id];
    }

    return $ids_arr;
}

$php_input = json_decode(file_get_contents('php://input'));
if ($php_input) {
    $like_id = $php_input->like ?? false;
    $cart_item_id = $php_input->cartItem ?? false;
    $order_items = $php_input->orderItems ?? false;

    if ($like_id) {
        $likes_arr = itemAction($like_id, $likes_arr);

        file_put_contents('db/likes.json', json_encode(array_values($likes_arr)));
    }

    if ($cart_item_id) {
        $cart_arr = itemAction($cart_item_id, $cart_arr);

        file_put_contents('db/cart.json', json_encode(array_values($cart_arr)));
    }

    if ($order_items) {
        $orders_arr = json_decode(file_get_contents('db/orders.json'), true);
        $order_id = !empty($orders_arr) ? end($orders_arr)['Order id'] + 1 : 1;
        $order_total = $php_input->totalPrice;
        $orders_arr[] = ['Order id' => $order_id, 'Items' => $order_items, 'Total' => $order_total];
        file_put_contents('db/orders.json', json_encode($orders_arr, JSON_UNESCAPED_UNICODE));
        file_put_contents('db/cart.json', null);
        echo $order_id;
        exit;
    }
}

if ($_GET) {
    if (isset($_GET['search'])) {
        $search_value = mb_strtolower(trim($_GET['search']));
        $sneakers_arr = array_values(array_filter($sneakers_arr, function ($item) use ($search_value) {
            return isset($item['title']) && str_contains(mb_strtolower($item['title']), $search_value);
        }));
    }

    if (isset($_GET['sortBy'])) {
        if ($_GET['sortBy'] === 'name') {
            usort($sneakers_arr, function ($a, $b) {
                return strcmp($a['title'], $b['title']);
            });
        } else if ($_GET['sortBy'] === 'asc' || $_GET['sortBy'] === 'desc') {
            $sortOrder = $_GET['sortBy'];
            usort($sneakers_arr, function ($a, $b) use ($sortOrder) {
                if ($sortOrder === 'asc') {
                    return $a['price'] <=> $b['price'];
                } else {
                    return $b['price'] <=> $a['price'];
                }
            });
        }
    }

    if (isset($_GET['getLikedItems'])) {
        $liked_items = [];
        $like_ids = array_column($likes_arr, 'id');
        $liked_items = array_filter($sneakers_arr, function ($item) use ($like_ids) {
            return in_array($item['id'], $like_ids);
        });

        $cart_ids = array_column($cart_arr, 'id');
        $cart_items = array_filter($sneakers_arr, function ($item) use ($cart_ids) {
            return in_array($item['id'], $cart_ids);
        });
        echo json_encode(['liked_items' => $liked_items, 'cart' => $cart_items]);
        exit;
    }
}

echo json_encode(['sneakers' => $sneakers_arr, 'likes' => $likes_arr, 'cart' => $cart_arr]);
