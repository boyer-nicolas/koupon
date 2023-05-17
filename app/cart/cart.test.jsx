import { describe, expect, test } from 'vitest'
import { render, screen } from '@testing-library/react';

import Cart from './page';

describe('Cart', () =>
{
    test('renders without crashing', () =>
    {
        render(<Cart />);
    });
}
);