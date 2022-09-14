import { Base64 } from 'js-base64';

import { backendApi } from '../api/backend/backendApi';
import { accountEmail, isAuthenticated } from './accountServices';

export function addToCart(reference, quantity) {
    if (isAuthenticated()) {
        return backendApi.lineCarts.addToCart({
            quantity: formInput.quantity,
            product: product.reference,
            cartUser: Base64.encodeURL(accountEmail()),
            add: true,
        });
    } else {
        var lineCarts = JSON.parse(localStorage.getItem('cart')) ?? [];

        if (lineCarts.length <= 0) {
            lineCarts.push({
                reference: reference,
                quantity: quantity,
            });
        } else {
            var AlreadyExists = false;
            lineCarts.forEach((element) => {
                if (element.reference == reference) {
                    element.quantity += quantity;
                    AlreadyExists = true;
                }
            });
            if (!AlreadyExists) {
                lineCarts.push({
                    reference: reference,
                    quantity: quantity,
                });
            }
        }

        localStorage.setItem('cart', JSON.stringify(lineCarts));
    }
}
