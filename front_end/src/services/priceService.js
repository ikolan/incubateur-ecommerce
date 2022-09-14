const CURRENCY = '€';

export function price(price) {
    return (price / 100).toFixed(2).toString().replace('.', ',') + ' ' + CURRENCY;
}

export function makePriceObject(price) {
    price = (price / 100).toFixed(2).toString();
    return {
        currency: CURRENCY,
        value: price.split('.')[0],
        cents: price.split('.')[1],
    };
}
