/**
 * Default value of forms.
 *
 * @author Nicolas Benoit
 */
export const initialFormValues = {
    /** Login form */
    login: {
        email: '',
        password: '',
    },

    /** User Registration */
    register: {
        firstName: '',
        lastName: '',
        phone: '',
        birthDate: '',
        email: '',
        password: '',
        passwordConfirmation: '',
    },

    /** Password modification */
    changePassword: {
        oldPassword: '',
        newPassword: '',
        repeatNewPassword: '',
    },

    forgotPasswordStepOne: {
        email: '',
    },

    forgotPasswordStepTwo: {
        password: '',
        passwordConfirmation: '',
    },

    /** Address */
    address: {
        title: '',
        number: 0,
        road: '',
        zipcode: '',
        city: '',
        phone: '',
    },

    addToCart: {
        quantity: 1,
    },

    /** Contact */
    contact: {
        firstName: '',
        lastName: '',
        email: '',
        subject: '',
        message: '',
    },

    /** Products */
    adminProduct: {
        name: '',
        reference: '',
        price: 0,
        tax: 0,
        description: '',
        detailedDescription: '',
        weight: 0,
        stock: 0,
        frontPage: false,
        category: null,
        tags: [],
    },

    /** Images */
    adminImage: {
        name: '',
        file: '',
    },

    adminOrder: {
        status: '',
    },

    /** Order Returns */
    orderReturn: {
        reason: '',
        description: '',
    },
};
