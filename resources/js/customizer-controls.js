/* global wp */
import { ExtendedControl } from './customizer-controls/control';
import { ResponsiveRangeControl } from './customizer-controls/responsive-range/control';

wp.customize.controlConstructor.themesetup_responsive_range_control = ResponsiveRangeControl; // eslint-disable-line camelcase
