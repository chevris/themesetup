/**
 * External dependencies
 */
import PropTypes from 'prop-types';
import classnames from 'classnames';

const RadioImage = ({ choices, onClick, value, label }) => {
	return (
		<>
			{label && <label className="customize-control-title">{label}</label>}
			<div className="themesetup-radio-image">
				{Object.keys( choices ).map( ( choice, index ) => {
					const { name, image, url } = choices[choice];
					const buttonClass = classnames([ { active: choice === value } ]);
					return (
						<div className="option" key={index}>
							<label data-option={choice}>
								<button
									className={buttonClass}
									onClick={ ( e ) => {
										e.preventDefault();
										onClick( choice );
									}}
								>
									<img
										src={image || url }
										alt={name || `Option ${choice}`}
									/>
								</button>
								{name && <span>{name}</span>}
							</label>
						</div>
					);
				})}
			</div>
		</>
	);
};

RadioImage.propTypes = {
	choices: PropTypes.object.isRequired,
	onClick: PropTypes.func.isRequired,
	value: PropTypes.string.isRequired
};

export default RadioImage;
