<?php
// Thumbnailer modified by @kennydude

# ImageThumb - a simple on-the-fly image thumbnailer in PHP.
# Copyright (C) 2003, 2004, 2005 Marco Olivo
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 
/***********************************************************************/
/* ImageThumb, v.1.0                                                   */
/* - creates a thumbnail of up to the given size of the given image,   */
/*   keeping the original aspect ratio.                                */
/* Copyright (C) by Marco Olivo, 2003-2005                             */
/* This sofware can be found at:                                       */
/* http://www.olivo.net/software/imagethumb                            */
/*                                                                     */
/* PARAMETERS                                                          */
/* ----------                                                          */
/* s - the path to the image to be resized (can also be an URL)        */
/* w - the max width desired (optional, default: 320)                  */
/* h - the max height desired (optional, default: 200)                 */
/*                                                                     */
/* GD-library compiled and loaded in PHP is REQUIRED.                  */
/*                                                                     */
/* This software is distributed under GPL license 2 or following.      */
/*                                                                     */
/***********************************************************************/
 
/* some settings */
ignore_user_abort();
set_time_limit( 0 );
error_reporting( FATAL | ERROR | WARNING );
 
/* security check */
ini_set( 'register_globals', '0' );
 
/* start buffered output */
ob_start();
 
/* temporary kludge */
//while ( list( $key, $val ) = each( $HTTP_GET_VARS ) ) $_GET[ $key ] = $val;
 
/* some checks */
if ( ! isset( $_GET['file'] ) ) die( 'Source image not specified' );
if ( isset( $_GET['width'] ) && ereg( "^[0-9]+$", $_GET['width'] ) ) $MAX_WIDTH = $_GET['width'];
else $MAX_WIDTH = 320; 
if ( isset( $_GET['height'] ) && ereg( "^[0-9]+$", $_GET['height'] ) ) $MAX_HEIGHT = $_GET['height'];
else $MAX_HEIGHT = 200;

$file = "content/media/${_GET[file]}"; 
$cache = "content/media/thumb/${_GET[file]}_${MAX_WIDTH}x${MAX_HEIGHT}.png";

if(file_exists($cache) && !$_GET['nocache']){
	header("Location: $cache");
	exit;
}
/* get source image size */
$src_size = getimagesize( $file );
 
/* resize the image (if needed) */
if ( $src_size[0] > $MAX_WIDTH && $src_size[1] > $MAX_HEIGHT ) {
        if ( $src_size[0] > $src_size[1] ) {
                $dest_width = $MAX_WIDTH;
                $dest_height = ( $src_size[1] * $MAX_WIDTH ) / $src_size[0];
        }
        else {
                $dest_width = ( $src_size[0] * $MAX_HEIGHT ) / $src_size[1];
                $dest_height = $MAX_HEIGHT;
        }
}
else if ( $src_size[0] > $MAX_WIDTH ) {
        $dest_width = $MAX_WIDTH;
        $dest_height = ( $src_size[1] * $MAX_WIDTH ) / $src_size[0];
}
else if ( $src_size[1] > $MAX_HEIGHT ) {
        $dest_width = ( $src_size[0] * $MAX_HEIGHT ) / $src_size[1];
        $dest_height = $MAX_HEIGHT;
}
else {
        $dest_width = $src_size[0];
        $dest_height = $src_size[1];
}
 
if ( extension_loaded( 'gd' ) ) {
        /* check the source file format */
        $ext = substr( $file, strrpos( $file, '.' ) + 1 );
        if ( $ext == 'jpg' || $ext == 'jpeg' ) $src = imagecreatefromjpeg( $file ) or die( 'Cannot load input JPEG image' );
        else if ( $ext == 'gif' ) $src = imagecreatefromgif( $file ) or die( 'Cannot load input GIF image' );
        else if ( $ext == 'png' ) $src = imagecreatefrompng( $file ) or die( 'Cannot load input PNG image' );
        else die( 'Unsupported source file format' );
 
        /* create and output the destination image */
        $dest = imagecreate( $dest_width, $dest_height ) or die( 'Cannot initialize new GD image stream' );
        imagecopyresized( $dest, $src, 0, 0, 0, 0, $dest_width, $dest_height, $src_size[0], $src_size[1] );
        if ( imagetypes() & IMG_PNG ) {
            header ( 'Content-type: image/png' );
            imagepng( $dest, $cache );
			imagepng($dest);
        }
        else if ( imagetypes() & IMG_JPG ) {
            header ( 'Content-type: image/jpeg' );
            imagejpeg( $dest );
        }
        else if ( imagetypes() & IMG_GIF ) {
                header ( 'Content-type: image/gif' );
                imagegif( $dest );
        }
        else print 'Cannot find a suitable output format';
}
else print 'GD-library support is not available';
 
/* end buffered output */
ob_end_flush();

