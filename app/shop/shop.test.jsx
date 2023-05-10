import { describe, expect, test } from 'vitest'
import { render, screen } from '@testing-library/react';

import Shop from './page';

describe('Shop', () =>
{
    test('renders without the clear cart button', () =>
    {
        render(<Shop />);
        expect(screen.queryByText(/Clear Cart/i)).toBeNull();
    });
}
);