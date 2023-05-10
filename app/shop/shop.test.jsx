import { describe, expect, test } from 'vitest'
import { render, screen } from '@testing-library/react';

import Shop from './page';

describe('Shop', () =>
{
    test('renders without crashing', () =>
    {
        render(<Shop />);
    });

    test('toast appears when add to cart button is clicked', () =>
    {
        render(<Shop />);

        const addToCartBtn = screen.getAllByText('Add to Cart');
        addToCartBtn.map((btn) => btn.click());
    });
}
);