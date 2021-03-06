/* global wp */
import { ExtendedControl } from './customizer-controls/control';
import { PresetsControl } from './customizer-controls/presets/control';
import { TitleControl } from './customizer-controls/title/control';
import { ToggleControl } from './customizer-controls/toggle/control';
import { RangeControl } from './customizer-controls/range/control';
import { ResponsiveRangeControl } from './customizer-controls/responsive-range/control';
import { FocusButtonControl } from './customizer-controls/focus-button/control';
import { IconCheckboxControl } from './customizer-controls/icon-checkbox/control';
import { RadioImageControl } from './customizer-controls/radio-image/control';

wp.customize.controlConstructor.themesetup_presets_control = PresetsControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_title_control = TitleControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_toggle_control = ToggleControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_range_control = RangeControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_responsive_range_control = ResponsiveRangeControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_focus_button_control = FocusButtonControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_icon_checkbox_control = IconCheckboxControl; // eslint-disable-line camelcase
wp.customize.controlConstructor.themesetup_radio_image_control = RadioImageControl; // eslint-disable-line camelcase

