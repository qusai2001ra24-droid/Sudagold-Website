import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: [],
        itemCount: 0,
        subtotal: 0,
        totalWeight: 0,
        tax: 0,
        total: 0,
        isOpen: false,
        
        async init() {
            await this.sync();
        },

        async sync() {
            try {
                const response = await fetch('/cart/data');
                const data = await response.json();
                
                this.items = data.items || [];
                this.itemCount = data.item_count || 0;
                this.subtotal = data.subtotal || 0;
                this.totalWeight = data.total_weight || 0;
                this.tax = data.tax || 0;
                this.total = data.total || 0;
            } catch (error) {
                console.error('Failed to sync cart:', error);
            }
        },

        async add(productId, quantity = 1) {
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.sync();
                    this.isOpen = true;
                }
                
                return data;
            } catch (error) {
                console.error('Failed to add to cart:', error);
                return { success: false, message: 'Failed to add to cart' };
            }
        },

        async updateQuantity(itemId, quantity) {
            try {
                const response = await fetch('/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.sync();
                }
                
                return data;
            } catch (error) {
                console.error('Failed to update quantity:', error);
                return { success: false, message: 'Failed to update quantity' };
            }
        },

        async remove(itemId) {
            try {
                const response = await fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.sync();
                }
                
                return data;
            } catch (error) {
                console.error('Failed to remove item:', error);
                return { success: false, message: 'Failed to remove item' };
            }
        },

        async clear() {
            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.sync();
                }
                
                return data;
            } catch (error) {
                console.error('Failed to clear cart:', error);
                return { success: false, message: 'Failed to clear cart' };
            }
        },

        toggle() {
            this.isOpen = !this.isOpen;
        },

        open() {
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        }
    });
});

Alpine.start();
