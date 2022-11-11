import { TEXT_DOMAIN } from "../../../globals";
import { toLower } from "lodash";
import { Component } from "@wordpress/element";
import { MediaUpload, MediaUploadCheck } from "@wordpress/editor";
import { Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

class LibraryPicker extends Component {
	render() {
		const { title, allowed, onSelect, value } = this.props;
		const instructions = (
			<p>
				{__(
					`To edit the ${toLower(title)}, you need permission to upload media.`,
					TEXT_DOMAIN
				)}
			</p>
		);

		return (
			<div className="ep-media-picker">
				<MediaUploadCheck fallback={instructions}>
					<MediaUpload
						title={__(title, TEXT_DOMAIN)}
						onSelect={onSelect}
						allowedTypes={allowed}
						value={value}
						render={({ open }) => (
							<Button
								className="editor-post-featured-image__toggle"
								onClick={open}
							>
								{__(`Set ${title}`, TEXT_DOMAIN)}
							</Button>
						)}
					/>
				</MediaUploadCheck>
			</div>
		);
	}
}

export default LibraryPicker;
