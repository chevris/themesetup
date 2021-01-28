/* global wp */
import { ExtendedControl } from './customizer-controls/control';
import { PresetsControl } from './customizer-controls/presets/control';
import { RangeControl } from './customizer-controls/range/control';
import { ResponsiveRangeControl } from './customizer-controls/responsive-range/control';

wp.customize.controlConstructor.themesetup_presets_control = PresetsControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_range_control = RangeControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_responsive_range_control = ResponsiveRangeControl; // eslint-disable-line camelcase
