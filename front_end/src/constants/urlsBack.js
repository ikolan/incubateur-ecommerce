// Addresses
export const URL_BACK_ADDRESS_COLLECTION = '/addresses';
export const URL_BACK_ADDRESS_MAIN = '/addresses/main';
export const URL_BACK_ADDRESS_ITEM = '/addresses/{id}';
export const URL_BACK_ADDRESS_MAIN_ITEM = '/addresses/{id}/main';

// Users
export const URL_BACK_USER_COLLECTION = '/users';
export const URL_BACK_USER_ITEM = '/users/{id}';
export const URL_BACK_USER_LOGIN = '/users/login';
export const URL_BACK_USER_ACTIVATION = '/users/activate';
export const URL_BACK_USER_RESET_KEY = '/users/{id}/reset-key';
export const URL_BACK_USER_GET_BY_RESET_KEY = '/users/by-reset-key/{resetKey}';
export const URL_BACK_USER_DEACTIVATION = '/users/{id}/deactivate';
export const URL_BACK_USER_REACTIVATION_REQUEST = '/users/{id}/reactivation-request';
export const URL_BACK_USER_CURRENT = '/users/{email}';

// Products
export const URL_BACK_PRODUCT_COLLECTION = '/products';
export const URL_BACK_PRODUCT_ITEM = '/products/{reference}';
export const URL_BACK_PRODUCT_PRICE_SLICE = URL_BACK_PRODUCT_COLLECTION + '/price-slice';

//Cart
export const URL_BACK_CART_COLLECTION = '/line_carts';
export const URL_BACK_CART_ITEM = '/line_carts/{id}';

// Categories
export const URL_BACK_CATEGORY_COLLECTION = '/categories';
export const URL_BACK_CATEGORY_ITEM = '/categories/{id}';

// Tags
export const URL_BACK_TAG_COLLECTION = '/tags';
export const URL_BACK_TAG_ITEM = '/tags/{id}';

// Contact
export const URL_BACK_CONTACT_COLLECTION = '/contacts';
export const URL_BACK_CONTACT_ITEM = '/contacts/{id}';

// Images
export const URL_BACK_IMAGE_COLLECTION = '/images';
export const URL_BACK_IMAGE_ITEM = '/images/{id}';
export const URL_BACK_IMAGE_ITEM_DATA = URL_BACK_IMAGE_ITEM + '/data';

//Statuses
export const URL_BACK_STATUS_COLLECTION = '/statuses';

// Orders
export const URL_BACK_ORDER_COLLECTION = '/orders';
export const URL_BACK_ORDER_ITEM = '/orders/{orderReference}';
export const URL_BACK_ORDER_PAYMENT_ITEM = '/orders/PaymentLink/{orderReference}';
export const URL_BACK_ORDER_UPDATE_STATUS = URL_BACK_ORDER_ITEM + '/update-status';
export const URL_BACK_ORDER_MAKE_RETURN_ORDER = URL_BACK_ORDER_ITEM + '/return';

// Order returns
export const URL_BACK_ORDER_RETURNS_COLLECTION = '/order_returns';
export const URL_BACK_ORDER_RETURNS_ITEM = '/order_returns/{id}';
