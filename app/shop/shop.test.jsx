import { describe, expect, test } from 'vitest'
import { render, screen } from '@testing-library/react';

import Shop from './page';

describe('Shop', () =>
{
    test('renders without crashing', () =>
    {
        render(<Shop />);
    });

    test('add to cart button exists', () =>
    {
        render(<Shop />);

        const addToCartBtn = screen.getAllByText('Add to Cart');
        let i = 0;
        addToCartBtn.map((btn) =>
        {
            btn.click();
            i++;
            if (i > 1)
            {
                return;
            }
        });
    });
}
);